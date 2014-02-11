<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entity;

use Granula\Meta;
use Granula\ActiveRecord;
use Granula\Setter;

/**
 * Class User
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $email
 * @property string $avatar
 * @property \Entity\Profile $profile
 * @property \Entity\User $friend
 * @property \DateTime $date
 *
 * @method User find($id) static
 */
class User
{
    use ActiveRecord;
    use Setter;

    private $id;
    private $name;
    private $password;
    private $email;
    private $avatar;
    private $profile;
    private $friend;
    private $date;

    public static function describe(Meta $meta)
    {
        $meta->table('users');
        $meta->field('id', 'integer')->primary()->options(['autoincrement' => true]);
        $meta->field('name', 'string');
        $meta->field('password', 'string');
        $meta->field('email', 'string')->unique()->options(['notnull' => true]);
        $meta->field('avatar', 'string')->options(['notnull' => false, 'default' => '']);
        $meta->field('profile', 'entity')->entity(Profile::class);
        $meta->field('friend', 'entity')->entity(User::class);
        $meta->field('date', 'datetime');
        $meta->index(['name', 'email'], 'name_email_index');
    }
} 