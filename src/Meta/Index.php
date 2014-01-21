<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula\Meta;

class Index 
{
    private $columns = [];
    private $name;
    private $flags = [];

    public function __construct($columns, $name, $flags = [])
    {
        $this->columns = $columns;
        $this->name = $name;
        $this->flags = $flags;
    }

    public function flags($flags)
    {
        $this->flags = $flags;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


} 