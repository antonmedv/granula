<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fixture;

use Granula\Meta;

class User
{
    protected $id;

    protected $name;

    protected $password;

    protected $email;

    protected $avatar;

    public static function describe(Meta $meta)
    {
        $meta->table('users');
        $meta->field('id', 'integer')->primary()->options(['autoincrement' => true]);
        $meta->field('name', 'string');
        $meta->field('password', 'string');
        $meta->field('email', 'string')->unique()->options(['notnull' => true]);
        $meta->field('avatar', 'string')->options(['notnull' => false, 'default' => '']);
        $meta->index(['name', 'email'], 'name_email_index');
    }
} 