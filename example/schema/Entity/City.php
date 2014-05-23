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
 * @property int $id
 * @property string $name
 */
class City
{
    use ActiveRecord;
    use Setter;

    private $id;
    private $name;

    public static function describe(Meta $meta)
    {
        $meta->table('city');
        $meta->field('id', Type::INTEGER)->primary();
        $meta->field('name', 'string');
    }
} 