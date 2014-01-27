<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

use Fixture\User;
use Granula\EntityManager\EntityManagerEventArgs;

class GranulaTest extends \PHPUnit_Framework_TestCase
{
    public function testGranula()
    {
        $evm = new EventManager();
        $evm->addEventListener(EntityManager\Events::preUpdateSchema, function (EntityManagerEventArgs $ea) {
            $sql = EntityManager::getInstance()->getSchemaTool()->getUpdateSchemaSql();

            print_r($sql);
        });

        $params = [
            'dev' => true,
            'event_manager' => $evm,

            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'dbname' => 'granula',
            'charset' => 'utf8'
        ];

        $em = new EntityManager($params, [
            User::class,
        ]);

        $user = User::find(1);
    }
}
 