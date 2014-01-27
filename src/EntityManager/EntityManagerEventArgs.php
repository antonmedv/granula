<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula\EntityManager;

use Doctrine\Common\EventArgs as BaseEventArgs;
use Granula\EntityManager;

class EntityManagerEventArgs extends BaseEventArgs
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @param \Granula\EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @return \Granula\EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }
}