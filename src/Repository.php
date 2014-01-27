<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

trait Repository 
{
    public static function find($id)
    {
        $em = EntityManager::getInstance();
        $meta = $em->getMetaForClass(get_called_class());
    }
}