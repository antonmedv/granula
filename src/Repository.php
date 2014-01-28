<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

trait Repository
{
    public static function find($id)
    {
        $em = EntityManager::getInstance();
        $meta = $em->getMetaForClass(get_called_class());

        $sql = '';

        $conn = $em->getConnection();
        $query = $conn->prepare($sql);

        $qb = $conn->createQueryBuilder();
        $qb->select();

        $query->bindParam(1, $id);
        $query->execute();

        $query->fetch();
    }

    public static function query($sql, $params = [], \Closure $map = null)
    {
        $em = EntityManager::getInstance();

        $query = $em->getConnection()->prepare($sql);
        $query->execute($params);

        while ($result = $query->fetch()) {
            yield $map($result);
        }
    }
}