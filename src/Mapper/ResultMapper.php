<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula\Mapper;

use Granula\Meta;
use Granula\Meta\Field;
use Granula\Type\EntityType;

class ResultMapper
{
    /**
     * @var array
     */
    private $map = [];

    /**
     * @var array
     */
    private $joins = [];

    /**
     * @var Meta
     */
    private $rootEntityMeta;

    /**
     * @var Meta[]
     */
    private $joinedEntityMeta = [];

    public function __construct()
    {
    }

    public function setRootEntity(Meta $rootMeta)
    {
        $this->rootEntityMeta = $rootMeta;
    }

    public function addJoinedEntity(Field $field, Meta $joinedMeta)
    {
        $this->joinedEntityMeta[] = [$field, $joinedMeta];
    }

    public function createMap()
    {
        $this->map = [];

        $rootMap = [];
        foreach ($this->rootEntityMeta->getFields() as $field) {
            $column = $this->rootEntityMeta->getAlias() . '_' . $field->getName();
            $rootMap[] = [$field, $column];
        }
        $this->map[$this->rootEntityMeta->getAlias()] = [$this->rootEntityMeta->getClass(), $rootMap];


        foreach ($this->joinedEntityMeta as list($joinField, $joinMeta)) {
            $joinMap = [];
            foreach ($joinMeta->getFields() as $field) {
                $column = $joinField->getName() . '_' . $joinMeta->getAlias() . '_' . $field->getName();
                $joinMap[] = [$field, $column];
            }

            $alias = $joinField->getName() . '_' . $joinMeta->getAlias();
            $this->map[$alias] = [$joinMeta->getClass(), $joinMap];
            $this->joins[] = [$this->rootEntityMeta->getAlias(), $joinField, $alias];
        }
    }

    public function map($result, $platform)
    {
        if (empty($this->map)) {
            $this->createMap();
        }

        $entities = [];
        $reflections = [];

        foreach ($this->map as $alias => $at) {
            list($class, $map) = $at;

            $rc = new \ReflectionClass($class);
            $entity = $rc->newInstance();

            /** @var $field Field */
            foreach ($map as list($field, $column)) {
                if (!isset($result[$column])) {
                    continue;
                }

                $value = $field->getType()->convertToPHPValue($result[$column], $platform);

                $this->setEntityValue($entity, $field->getName(), $value, $rc);
            }

            $entities[$alias] = $entity;
            $reflections[$alias] = $rc;
        }

        foreach ($this->joins as list($alias, $field, $to)) {
            $this->setEntityValue($entities[$alias], $field->getName(), $entities[$to], $reflections[$alias]);
        }

        return $entities[$this->rootEntityMeta->getAlias()];
    }

    private function setEntityValue($entity, $fieldName, $value, \ReflectionClass $class)
    {
        $property = $class->getProperty($fieldName);
        $property->setAccessible(true);
        $property->setValue($entity, $value);
    }
}

