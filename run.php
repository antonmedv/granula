<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/** @var $autoload \Composer\Autoload\ClassLoader */
$autoload = require_once __DIR__ . '/vendor/autoload.php';
$autoload->add('Fixture', __DIR__ . '/test/');
echo "==============================================================\n\n";

use Doctrine\DBAL\Query\QueryBuilder;
use Fixture\User;
use Granula\EntityManager;
use Granula\EventManager;

$evm = new EventManager();
$evm->addEventListener(EntityManager\Events::preUpdateSchema, function () {
    $sql = EntityManager::getInstance()->getSchemaTool()->getUpdateSchemaSql();
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

//$user = User::find(1);

/** @var $res Generator */
$result = User::query('SELECT * FROM users u WHERE u.id = ?', [2], function ($row) {
    $user = new User();
    $user->setId($row['id']);
    $user->setName($row['name']);
    $user->setPassword($row['password']);
    $user->setEmail($row['email']);
    $user->setAvatar($row['avatar']);
    return $user;
});

$user = $result->current();
var_dump($user);