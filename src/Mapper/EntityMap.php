<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula\Mapper;

use Granula\Meta\Field;

class EntityMap
{
    private $class;

    private $alias;

    private $map;

    public function __construct($class, $alias)
    {
        $this->class = $class;
        $this->alias = $alias;
    }

    public function addField(Field $field, $column)
    {
        $this->map[] = [$field, $column];
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return mixed
     */
    public function getMap()
    {
        return $this->map;
    }


} 