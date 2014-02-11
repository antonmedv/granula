<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\Visitor\DropSchemaSqlCollector;

class SchemaTool
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @var \Doctrine\DBAL\Platforms\AbstractPlatform
     */
    private $platform;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->connection = $this->em->getConnection();
        $this->platform = $this->connection->getDatabasePlatform();
    }

    /**
     * Creates the database schema for the given array of classes.
     */
    public function createSchema()
    {
        foreach ($this->getCreateSchemaSql() as $sql) {
            $this->connection->executeQuery($sql);
        }
    }

    public function updateSchema($saveMode = false)
    {
        foreach ($this->getUpdateSchemaSql($saveMode) as $sql) {
            $this->connection->executeQuery($sql);
        }
    }

    /**
     * Gets the list of DDL statements that are required to create the database schema for
     * the given list of classes.
     *
     * @return array The SQL statements needed to create the schema for the classes.
     */
    public function getCreateSchemaSql()
    {
        $platform = $this->em->getConnection()->getDatabasePlatform();
        $schema = $this->getSchema();
        return $schema->toSql($platform);
    }

    /**
     * Gets the sequence of SQL statements that need to be performed in order
     * to bring the given class mappings in-synch with the relational schema.
     * If $saveMode is set to true the command is executed in the Database,
     * else SQL is returned.
     *
     * @param boolean $saveMode True for writing to DB, false for SQL string.
     *
     * @return array The sequence of SQL statements.
     */
    public function getUpdateSchemaSql($saveMode = false)
    {
        $sm = $this->connection->getSchemaManager();

        $fromSchema = $sm->createSchema();
        $toSchema = $this->getSchema();

        $comparator = new Comparator();
        $schemaDiff = $comparator->compare($fromSchema, $toSchema);

        if ($saveMode) {
            return $schemaDiff->toSaveSql($this->platform);
        }

        return $schemaDiff->toSql($this->platform);
    }

    public function getSchema()
    {
        $schema = new Schema();

        /** @var $tables Table[] */
        $tables = [];
        $foreignKeys = [];

        // Create all tables and save them to $tables.
        foreach ($this->em->getMetaForAllClasses() as $fromClass => $meta) {

            $tables[$fromClass] = $table = $schema->createTable($meta->getTable());
            $primaryKeys = [];
            $uniqueKeys = [];

            foreach ($meta->getFields() as $field) {
                $table->addColumn($field->getName(), $field->getTypeName(), $field->getOptions());

                if ($field->isPrimary()) {
                    $primaryKeys[] = $field->getName();
                }

                if ($field->isUnique()) {
                    $uniqueKeys[] = $field->getName();
                }

                if ($field->isForeignKey()) {
                    $foreignKeys[] = [$fromClass, $field->getName(), $field->getEntityClass()];
                }
            }

            if (!empty($primaryKeys)) {
                $table->setPrimaryKey($primaryKeys);
            }

            if (!empty($uniqueKeys)) {
                $table->addUniqueIndex($uniqueKeys);
            }

            foreach ($meta->getIndexes() as $index) {
                $table->addIndex($index->getColumns(), $index->getName(), $index->getFlags());
            }
        }

        // Add foreign key constraint after creating all tables.
        foreach ($foreignKeys as $list) {
            list($fromClass, $field, $toClass) = $list;

            $tables[$fromClass]->addForeignKeyConstraint(
                $tables[$toClass],
                [$field],
                [$this->em->getMetaForClass($toClass)->getPrimaryField()->getName()],
                ["onUpdate" => "CASCADE"]
            );
        }

        return $schema;
    }

    /**
     * Drops the database schema for the given classes.
     *
     * In any way when an exception is thrown it is suppressed since drop was
     * issued for all classes of the schema and some probably just don't exist.
     *
     * @return void
     */
    public function dropSchema()
    {
        $dropSchemaSql = $this->getDropSchemaSQL();
        $conn = $this->em->getConnection();

        foreach ($dropSchemaSql as $sql) {
            try {
                $conn->executeQuery($sql);
            } catch (\Exception $e) {

            }
        }
    }

    /**
     * Drops all elements in the database of the current connection.
     *
     * @return void
     */
    public function dropDatabase()
    {
        $dropSchemaSql = $this->getDropDatabaseSQL();
        $conn = $this->em->getConnection();

        foreach ($dropSchemaSql as $sql) {
            $conn->executeQuery($sql);
        }
    }

    /**
     * Gets the SQL needed to drop the database schema for the connections database.
     *
     * @return array
     */
    public function getDropDatabaseSQL()
    {
        $sm = $this->connection->getSchemaManager();
        $schema = $sm->createSchema();

        $visitor = new DropSchemaSqlCollector($this->platform);
        $schema->visit($visitor);

        return $visitor->getQueries();
    }

    /**
     * Gets SQL to drop the tables defined by the passed classes.
     *
     * @return array
     */
    public function getDropSchemaSQL()
    {
        $visitor = new DropSchemaSqlCollector($this->platform);
        $schema = $this->getSchema();

        $sm = $this->em->getConnection()->getSchemaManager();
        $fullSchema = $sm->createSchema();

        foreach ($fullSchema->getTables() as $table) {
            if (!$schema->hasTable($table->getName())) {
                foreach ($table->getForeignKeys() as $foreignKey) {
                    /* @var $foreignKey \Doctrine\DBAL\Schema\ForeignKeyConstraint */
                    if ($schema->hasTable($foreignKey->getForeignTableName())) {
                        $visitor->acceptForeignKey($table, $foreignKey);
                    }
                }
            } else {
                $visitor->acceptTable($table);
                foreach ($table->getForeignKeys() as $foreignKey) {
                    $visitor->acceptForeignKey($table, $foreignKey);
                }
            }
        }

        if ($this->platform->supportsSequences()) {
            foreach ($schema->getSequences() as $sequence) {
                $visitor->acceptSequence($sequence);
            }

            foreach ($schema->getTables() as $table) {
                /* @var $sequence Table */
                if ($table->hasPrimaryKey()) {
                    $columns = $table->getPrimaryKey()->getColumns();
                    if (count($columns) == 1) {
                        $checkSequence = $table->getName() . "_" . $columns[0] . "_seq";
                        if ($fullSchema->hasSequence($checkSequence)) {
                            $visitor->acceptSequence($fullSchema->getSequence($checkSequence));
                        }
                    }
                }
            }
        }

        return $visitor->getQueries();
    }
} 