<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventManager as BaseEventManager;

class EventManager extends BaseEventManager
{
    public function dispatchEvent($eventName, EventArgs $eventArgs = null)
    {
        if ($this->hasListeners($eventName)) {
            $eventArgs = $eventArgs === null ? EventArgs::getEmptyInstance() : $eventArgs;

            foreach ($this->getListeners($eventName) as $listener) {
                if ($listener instanceof \Closure) {
                    $listener($eventArgs);
                } else {
                    $listener->$eventName($eventArgs);
                }
            }
        }
    }
}