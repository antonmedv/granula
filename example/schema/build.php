<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/** @var $autoload \Composer\Autoload\ClassLoader */
$autoload = require_once __DIR__ . '/../../vendor/autoload.php';
$autoload->add('Entity', __DIR__);
echo "==============================================================\n\n";

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Entity\Address;
use Entity\Profile;
use Entity\User;
use Granula\EntityManager;
use Granula\EventManager;
use Granula\Mapper\ResultMapper;

$evm = new EventManager();
$evm->addEventListener(EntityManager\Events::preUpdateSchema, function () {
    if (!empty($sql = EntityManager::getInstance()->getSchemaTool()->getUpdateSchemaSql())) {
        //echo "UPDATE SCHEMA:\n";
        //var_dump($sql);
    }
});

$params = [
    'dev' => true,
    'event_manager' => $evm,
    'sql_logger' => function ($sql, $params = []) {
            //echo "• $sql · " . json_encode($params) . "\n";
        },

    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'dbname' => 'granula',
    'charset' => 'utf8'
];

$em = new EntityManager($params, [
    User::class,
    Profile::class,
    Address::class,
]);


$sql = EntityManager::getInstance()->getSchemaTool()->getCreateSchemaSql();

$c = '';
foreach ($sql as $i) {
    $c .= "$i\n";
}
file_put_contents('schema.sql', $c);