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
 * @property string $address
 * @property int $age
 * @property array $tags
 * @property User $user
 *
 * @method User find($id) static
 */
class Address
{
    use ActiveRecord;
    use Setter;

    private $id;
    private $country;
    private $city;
    private $street;

    public static function describe(Meta $meta)
    {
        $meta->table('address');
        $meta->field('id', Type::INTEGER)->primary();
        $meta->field('country', Type::STRING);
        $meta->field('city', Type::STRING);
        $meta->field('street', Type::STRING);
    }
} 