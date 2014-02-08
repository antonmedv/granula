<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fixture;

use Doctrine\DBAL\Types\Type;
use Granula\ActiveRecord;
use Granula\Meta;
use Granula\Type\EntityType;

class Profile
{
    use ActiveRecord;

    public $id;
    public $city;
    public $date;
    public $age;
    public $tags;
    public $user;

    public static function describe(Meta $meta)
    {
        $meta->table('profile');
        $meta->field('id', 'integer')->primary();
        $meta->field('city', 'string');
        $meta->field('date', 'datetime');
        $meta->field('age', Type::INTEGER);
        $meta->field('tags', Type::SIMPLE_ARRAY);
        $meta->field('user', EntityType::name)->entity(User::class);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        $this->load('user');
        return $this->user;
    }
} 