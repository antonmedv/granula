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
     * @param callable $map
     * @return \Generator
     */
    public static function query($sql, $params = [], \Closure $map = null)
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

        $query = $em->getConnection()->prepare($sql);
        $query->execute($params);

        while ($result = $query->fetch()) {
            yield $map($result);
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