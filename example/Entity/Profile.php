<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entity;

use Doctrine\DBAL\Types\Type;
use Granula\ActiveRecord;
use Granula\Meta;
use Granula\Setter;
use Granula\Type\EntityType;

/**
 * Class Profile
 * @property int $id
 * @property string $city
 * @property \DateTime $date
 * @property int $age
 * @property array $tags
 * @property User $user
 *
 * @method User find($id) static
 */
class Profile
{
    use ActiveRecord;
    use Setter;

    private $id;
    private $city;
    private $date;
    private $age;
    private $tags;
    private $user;

    public static function describe(Meta $meta)
    {
        $meta->table('profile');
        $meta->field('id', Type::INTEGER)->primary();
        $meta->field('city', Type::STRING);
        $meta->field('date', Type::DATETIME);
        $meta->field('age', Type::INTEGER);
        $meta->field('tags', Type::SIMPLE_ARRAY);
        $meta->field('user', EntityType::name)->entity(User::class);
    }
} 