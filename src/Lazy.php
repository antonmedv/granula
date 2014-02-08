<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

class Lazy 
{
    private $class;
    private $id;

    public function __construct($class, $id)
    {
        $this->class = $class;
        $this->id = $id;
    }

    public function load()
    {
        $class = $this->class;
        return $class::find($this->id);
    }
} 