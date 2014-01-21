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
        foreach($this->getCreateSchemaSql() as $sql) {
            $this->connection->executeQuery($sql);
        }
    }

    public function updateSchema($saveMode = false)
    {
        foreach($this->getUpdateSchemaSql($saveMode) as $sql) {
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

        $classes = $this->em->getClasses();

        foreach ($classes as $class) {
            $meta = new Meta();
            $class::describe($meta);

            $table = $schema->createTable($meta->getTable());
            $primaryKeys = [];
            $uniqueKeys = [];

            foreach ($meta->getFields() as $field) {
                $table->addColumn($field->getName(), $field->getTypeName(), $field->getOptions());

                if ($field->getPrimary()) {
                    $primaryKeys[] = $field->getName();
                }

                if ($field->getUnique()) {
                    $uniqueKeys[] = $field->getName();
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

        return $schema;
    }
} 