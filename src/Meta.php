<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

use Granula\Meta\Field;
use Granula\Meta\Index;

class Meta
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $table;

    /**
     * @var Field[]
     */
    private $fields = [];

    /**
     * @var Index[]
     */
    private $indexes = [];

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function table($table)
    {
        $this->table = $table;
    }

    public function field($name, $type = null)
    {
        return $this->fields[$name] = new Field($name, $type);
    }

    public function index($columns, $name)
    {
        return $this->indexes[$name] = new Index($columns, $name);
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getAlias()
    {
        return $this->table[0];
    }

    public function getPrimaryField()
    {
        foreach ($this->fields as $field) {
            if ($field->isPrimary()) {
                return $field;
            }
        }

        throw new \RuntimeException('Entity does not contain primary field.');
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return Index[]
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

}