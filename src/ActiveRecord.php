<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

use Doctrine\DBAL\Query\QueryBuilder;
use Granula\Mapper\Mapper;
use Granula\Mapper\ResultMapper;
use Granula\Type\EntityType;

trait ActiveRecord
{
    /**
     * @param string $sql
     * @param array $params
     * @param array $types
     * @param callable $map
     * @return \Generator
     */
    public static function query($sql, $params = [], $types = [], $map = null)
    {
        $em = EntityManager::getInstance();
        $conn = $em->getConnection();

        // Use mapper for current meta class.
        if (null === $map) {
            $map = function ($result) {
                return $result;
            };
        } elseif ($map instanceof ResultMapper) {
            $mapper = $map;
            $map = function ($result) use ($mapper, $conn) {
                return $mapper->map($result, $conn->getDatabasePlatform());
            };
        }

        $query = $conn->executeQuery($sql, $params, $types);

        while ($result = $query->fetch()) {
            yield $map($result);
        }
    }

    /**
     *  Update row.
     */
    public function save()
    {
        $this->insertOrUpdate(false);
    }

    /**
     * Insert new row.
     */
    public function create()
    {
        $this->insertOrUpdate(true);
    }

    /**
     * @param bool $isNewEntity Insert or update row.
     */
    private function insertOrUpdate($isNewEntity)
    {
        $meta = self::meta();
        $em = EntityManager::getInstance();
        $conn = $em->getConnection();
        $primary = $meta->getPrimaryField()->getName();

        $data = [];
        foreach ($meta->getFields() as $name => $field) {
            $data[$name] = $field->getType()->convertToDatabaseValue($this->$name, $conn->getDatabasePlatform());
        }

        if ($isNewEntity) {
            $conn->insert($meta->getTable(), $data);
            $this->$primary = $conn->lastInsertId();
        } else {
            $conn->update($meta->getTable(), $data, [$primary => $this->$primary]);
        }
    }


    /**
     * @return \Generator
     */
    public static function all()
    {
        $meta = self::meta();
        $qb = self::createQueryBuilder();

        $qb
            ->select($meta->getSelect())
            ->from($meta->getTable(), $meta->getAlias());

        return self::query($qb->getSQL());
    }

    /**
     * @param integer $id
     * @return mixed|null
     */
    public static function find($id)
    {
        if (null === $id) {
            return null;
        }

        $meta = self::meta();
        $mapper = new ResultMapper();
        $qb = self::createQueryBuilder();

        $qb
            ->select($meta->getSelect())
            ->from($meta->getTable(), $meta->getAlias())
            ->where($qb->expr()->eq(
                $meta->getPrimaryFieldNameWithAlias(),
                '?'
            ))
            ->setMaxResults(1);

        $mapper->setRootEntity($meta);

        foreach ($meta->getFieldsWhatHasEntities() as $field) {
            $class = $field->getEntityClass();
            /** @var $entityMeta Meta */
            $entityMeta = $class::meta();

            $qb->addSelect($entityMeta->getJoinSelect($field));
            $qb->leftJoin($meta->getAlias(), $entityMeta->getTable(), $entityMeta->getAlias(),
                $qb->expr()->eq(
                    $meta->getAlias() . '.' . $field->getName(),
                    $entityMeta->getPrimaryFieldNameWithAlias()
                )
            );

            $mapper->addJoinedEntity($field, $entityMeta);
        }

        $result = self::query($qb->getSQL(), [$id], [\PDO::PARAM_INT], $mapper);
        return $result->valid() ? $result->current() : null;
    }

    /**
     * @return QueryBuilder
     */
    public static function createQueryBuilder()
    {
        $em = EntityManager::getInstance();
        return $em->getConnection()->createQueryBuilder();
    }

    /**
     * @return Meta
     */
    public static function meta()
    {
        $em = EntityManager::getInstance();
        return $em->getMetaForClass(get_called_class());
    }
}