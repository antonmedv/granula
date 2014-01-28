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
    private $entityManager;

    public function __construct($em)
    {
        $this->entityManager = $em;
    }

    /**
     * @param \Granula\EntityManager $em
     */
    public function setEntityManager($em)
    {
        $this->entityManager = $em;
    }

    /**
     * @return \Granula\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}