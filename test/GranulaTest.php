<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

use Fixture\User;

class GranulaTest extends \PHPUnit_Framework_TestCase
{
    public function testGranula()
    {
        $config = new \Doctrine\DBAL\Configuration();
        $params = [
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'dbname' => 'granula',
            'charset' => 'utf8'
        ];
        $connection = \Doctrine\DBAL\DriverManager::getConnection($params, $config);

        $em = new EntityManager($connection, [
            User::class,
        ]);

        $st = $em->getSchemaTool();
        $sql = $st->getUpdateSchemaSql();
        $st->updateSchema();
        //$st->dropSchema();

        print_r($sql);
    }
}
 