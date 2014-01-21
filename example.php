<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Query
$pdo = new PDO('');
$query = $pdo->query('SELECT * FORM User');
$query->execute();
$rows = $query->fetchAll();

function queryBuilder() {
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
    $connection->getSchemaManager()

    return new \Doctrine\DBAL\Query\QueryBuilder($connection);
}




// One:
User::find(1);

// Multi:
User::findAll(['name' => 'Anton']);

// Create:
$user = new User();
$user->setName('Anton');
$user->save();

// Update
$user->setPassword('new one');
$user->save();

// With
$user = User::findAll()->with('invitedBy');


// Class:

class User
{
    protected $id;

    protected $name;

    protected $password;

    protected $email;

    protected $avatar;

    protected $friends;

    protected $invitedBy;

    public function __construct()
    {
    }

    public static function describe()
    {
        return [
            'id' => 'int primary',
            'name' => 'string',
            'password' => 'string',
            'email' => 'string',
            'avatar' => 'string',
            'friends' => 'many',
            'invitedBy' => 'hasOnce',
        ];
    }


    public static function find($id)
    {
        $qb = queryBuilder();
        $result = $qb->select('u.*')->from('users', 'u')->where('u.id = ?')->setParameter(1, $id)->execute();
        $row = $result->fetch();
    }

    /**
     * @return mixed
     */
    public function getFriends()
    {
        return self::lazy('friends');
    }


    /**
     * @return User
     */
    public function getInvitedBy()
    {
        return self::lazy('invitedBy');
    }
}



// Map
foreach ($rows as $row) {
    $user = new User();
    $user->setName($row['name']);
}