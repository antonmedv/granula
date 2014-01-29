<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

use Doctrine\DBAL\Query\QueryBuilder;
use Granula\Mapper\ResultMapper;

trait Repository
{
    /**
     * @param string $sql
     * @param array $params
     * @param array $types
     * @param callable $map
     * @return \Generator
     */
    public static function query($sql, $params = [], $types = [], \Closure $map = null)
    {
        $em = EntityManager::getInstance();
        $class = get_called_class();
        $meta = $em->getMetaForClass($class);

        if (null === $map) {
            $map = function ($result) use ($class, $meta) {
                $rm = new ResultMapper($class);

                $columnsToMap = [];

                foreach ($meta->getFields() as $field) {
                    $column = $field->getName();
                    $rm->addField($column);

                    // Collect columns what will be mapped
                    $columnsToMap[$column] = $result[$column];
                    unset($result[$column]);
                }

                $entity = $rm->map($columnsToMap);

                return empty($result) ? $entity : [$entity, $result];
            };
        }

        $query = $em->getConnection()->executeQuery($sql, $params, $types);

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
            $data[$name] = $this->$name;
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
            ->select('*')
            ->from($meta->getTable(), $meta->getAlias());

        return self::query($qb->getSQL());
    }

    /**
     * @param integer $id
     * @return mixed|null
     */
    public static function find($id)
    {
        $meta = self::meta();
        $qb = self::createQueryBuilder();

        $qb
            ->select('*')
            ->from($meta->getTable(), $meta->getAlias())
            ->where($qb->expr()->eq(
                $meta->getPrimaryField()->getName(),
                '?'
            ))
            ->setMaxResults(1);

        $result = self::query($qb->getSQL(), [$id]);
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