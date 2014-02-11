<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

trait Setter
{
    public function __get($name)
    {
        /** @var $field Meta\Field */
        $field = self::meta()->getFieldByName($name);

        if ($field && $field->isForeignKey()) {
            $this->load($field->getName());
        }

        $methodName = 'get' . ucfirst($name);
        return method_exists($this, $methodName) ? $this->{$methodName}() : $this->{$name};
    }

    public function __set($name, $value)
    {
        /** @var $field Meta\Field */
        $field = self::meta()->getFieldByName($name);

        if ($field && $field->isForeignKey()) {
            $class = $field->getEntityClass();
            $lazy = Lazy::class;
            if ($value instanceof $class) {
                // All right
            } elseif ($value instanceof $lazy) {
                // All right
            } else {
                throw new \InvalidArgumentException("Field '$name' must be '$class'.");
            }
        }

        $methodName = 'set' . ucfirst($name);
        return method_exists($this, $methodName) ? $this->{$methodName}($value) : $this->{$name} = $value;
    }

    public function __isset($name)
    {
        return property_exists($this, $name);
    }
} 