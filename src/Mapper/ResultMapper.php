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
     * @var EntityMap[]
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

    private $useAs;

    public function __construct()
    {
    }

    public function setRootEntity(Meta $rootMeta, $useAs = true)
    {
        $this->rootEntityMeta = $rootMeta;
        $this->useAs = $useAs;
    }

    public function addJoinedEntity(Field $field, Meta $joinedMeta, $alias)
    {
        $this->joinedEntityMeta[] = [$field, $joinedMeta, $alias];
        $this->joins[$alias] = $field->getName();
    }

    public function createMap()
    {
        $this->map = [];

        $rootMap = new EntityMap($this->rootEntityMeta->getClass(), $this->rootEntityMeta->getAlias());
        foreach ($this->rootEntityMeta->getFields() as $field) {
            $column = $this->useAs ? $this->rootEntityMeta->getAlias() . '_' . $field->getName() : $field->getName();
            $rootMap->addField($field, $column);
        }
        $this->map[] = $rootMap;


        foreach ($this->joinedEntityMeta as $list) {
            /** @var $joinField Field */
            /** @var $joinMeta Meta */
            list($joinField, $joinMeta, $alias) = $list;

            $joinMap = new EntityMap($joinMeta->getClass(), $alias);
            foreach ($joinMeta->getFields() as $field) {
                $column = $alias . '_' . $field->getName();
                $joinMap->addField($field, $column);
            }

            $this->map[] = $joinMap;
        }
    }

    public function map($result, $platform)
    {
        if (empty($this->map)) {
            $this->createMap();
        }

        $entities = [];
        $reflections = [];

        foreach ($this->map as $entityMap) {
            $rc = new \ReflectionClass($entityMap->getClass());
            $entity = $rc->newInstance();

            /** @var $field Field */
            foreach ($entityMap->getMap() as list($field, $column)) {
                if (!isset($result[$column])) {
                    continue;
                }

                $value = $field->getType()->convertToPHPValue($result[$column], $platform);

                $this->setEntityValue($entity, $field->getName(), $value, $rc);
            }

            $entities[$entityMap->getAlias()] = $entity;
            $reflections[$entityMap->getAlias()] = $rc;
        }

        $root = $entities[$this->rootEntityMeta->getAlias()];

        foreach ($this->joins as $alias => $fieldName) {
            $rc = $reflections[$this->rootEntityMeta->getAlias()];
            $current = $this->getEntityValue($root, $fieldName, $rc);
            if (null !== $current) {
                $this->setEntityValue($root, $fieldName, $entities[$alias], $rc);
            }
        }

        return $root;
    }

    private function getEntityValue($entity, $fieldName, \ReflectionClass $class)
    {
        $property = $class->getProperty($fieldName);
        $property->setAccessible(true);
        return $property->getValue($entity);
    }

    private function setEntityValue($entity, $fieldName, $value, \ReflectionClass $class)
    {
        $property = $class->getProperty($fieldName);
        $property->setAccessible(true);
        $property->setValue($entity, $value);
    }
}

