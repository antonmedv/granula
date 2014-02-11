<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula\Meta;

use Granula\Meta;

class Accessor
{
    private $meta;
    private $rc;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
        $this->rc = new \ReflectionClass($meta->getClass());
    }

    public static function create(Meta $meta)
    {
        return new self($meta);
    }

    public function getField($entity, $fieldName)
    {
        $property = $this->rc->getProperty($fieldName);
        $property->setAccessible(true);
        return $property->getValue($entity);
    }

    public function setField($entity, $fieldName, $value)
    {
        $property = $this->rc->getProperty($fieldName);
        $property->setAccessible(true);
        $property->setValue($entity, $value);
    }

    public function getPrimary($entity)
    {
        $name = $this->meta->getPrimaryField()->getName();
        return $this->getField($entity, $name);
    }
} 