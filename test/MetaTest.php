<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

use Granula\Meta\Field;

class MetaTest extends \PHPUnit_Framework_TestCase
{

    public function testMetaPrimaryField()
    {
        $meta = new Meta();
        $meta->table('test');
        $meta->field('id', 'integer')->primary();

        $this->assertInstanceOf(Field::class, $meta->getPrimaryField());
    }
}
 