<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fixture;

use Granula\ActiveRecord;
use Granula\Meta;

class Profile
{
    use ActiveRecord;

    public $id;
    public $user_id;

    public static function describe(Meta $meta)
    {
        $meta->table('profile');
        $meta->field('id', 'integer')->primary()->options(['autoincrement' => true]);
        $meta->field('user_id', 'string');
    }
} 