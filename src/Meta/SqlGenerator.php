<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula\Meta;

use Granula\Meta;

class SqlGenerator
{
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }

    /**
     * @return string
     */
    public function getPrimaryFieldNameWithAlias($alias = null)
    {
        return
            ($alias === null ? $this->meta->getAlias() : $alias)
            . '.'
            . $this->meta->getPrimaryField()->getName();
    }

    public function getSelect($alias = null)
    {
        $select = [];

        foreach ($this->meta->getFields() as $field) {
            $select[] =
                ($alias === null ? $this->meta->getAlias() : $alias)
                . '.'
                . $field->getName()
                . ' AS '
                . ($alias === null ? $this->meta->getAlias() : $alias)
                . '_'
                . $field->getName();
        }

        return $select;
    }
} 