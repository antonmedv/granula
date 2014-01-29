<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula\Mapper;

class ResultMapper
{
    private $entityClass;

    private $map = [];

    public function __construct($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    public function addField($column, $field = null)
    {
        $field = $field ? : $column;

        $this->map[$column] = $field;

        return $this;
    }

    public function map($row)
    {
        $class = new \ReflectionClass($this->entityClass);
        $entity = $class->newInstance();

        foreach ($this->map as $column => $field) {
            $property = $class->getProperty($field);
            $property->setAccessible(true);
            $property->setValue($entity, $row[$column]);
        }

        return $entity;
    }
}

