# Granula PHP Object Relational Mapper

![Problem](/docs/problem.png)

Common Object Relational Mapper generates query to database and mapping result to objects.
![ORM](/docs/orm.png)

Every row in database table represented by a single object in application.
![Schema](/docs/table_to_object.png)

Granula ORM using DBAL (Database Abstraction & Access Layer) and provide automatic SQL generation, automatic mapping and manual SQL writing with automatic/manual mapping.

In Granula ORM represented:
- [x] Auto schema generation
- [x] Auto schema upgrade
- [x] Active Record implementation
- [ ] Data Mapper implementation
- [x] Field mapping
- [x] Field types
- [x] Application <--> Database transformation
- [x] One-to-One association
- [x] Lazy and eager loading for one-to-one association
- [ ] One-to-Many association
- [ ] Many-to-May association


## Examples

[User.php](/example/Entity/User.php) and [Profile.php](/example/Entity/Profile.php) stored in `example` directory.

Creation of entities
```php
$profile = new Profile();
$profile->age = 21;
$profile->tags = ['one', 'two'];
$profile->date = new DateTime();
$profile->city = 'Saint Petersburg';
$profile->create();

$user = new User();
$user->name = 'Anton';
$user->email = uniqid();
$user->password = '1234';
$user->avatar = null;
$user->profile = $profile;
$user->friend = User::lazy(1);
$user->date = new DateTime('now');
$user->create();
```

Find user by primary key
```php
$user = User::find(1);
$user->profile->tags = ['one', 'two'];
$user->profile->save();
```

Manual mapping
```php
$users = User::query('SELECT * FROM users u WHERE u.id > ?', [1], [\PDO::PARAM_INT], function ($result) {
    $user = new User();
    $user->id = $result['id'];
    $user->name = $result['name'];
    $user->email = $result['email'];
    // ...
    $user->profile = Profile::lazy($result['profile']);
    return $user;
});

foreach($users as $user) {
    print_r($user);
}
```

Automatic mapping
```php
$result = User::query('SELECT * FROM users u WHERE u.id IN (?)', [[1, 2]], [Connection::PARAM_INT_ARRAY]);

/** @var $user User */
foreach ($result as $user) {
    $p = $user->profile;
    if($p instanceof Profile) {
        $u = $p->user->profile->user;
        print_r($u);
    }
}
```
This example will run only 2 query
```sql
• SELECT * FROM users u WHERE u.id IN (?)
• SELECT p.id AS p_id, p.city AS p_city, p.date AS p_date, p.age AS p_age, p.tags AS p_tags, p.user AS p_user, user.id AS user_id, user.name AS user_name, user.password AS user_password, user.email AS user_email, user.avatar AS user_avatar, user.profile AS user_profile, user.friend AS user_friend, user.date AS user_date FROM profile p LEFT JOIN users user ON p.user = user.id WHERE p.id = ? LIMIT 1
```