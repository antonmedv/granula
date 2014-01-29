<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula\Meta;

class Field
{
    private $name;

    private $typeName;

    private $primary = false;

    private $unique = false;

    private $options = [];

    public function __construct($name, $typeName)
    {
        $this->name = $name;
        $this->typeName = $typeName;
    }

    public function primary()
    {
        $this->primary = true;
        return $this;
    }

    public function unique()
    {
        $this->unique = true;
        return $this;
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->primary;
    }

    /**
     * @return mixed
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @return boolean
     */
    public function isUnique()
    {
        return $this->unique;
    }

}