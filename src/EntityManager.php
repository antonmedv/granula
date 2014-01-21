<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

use Doctrine\DBAL\Connection;

class EntityManager
{
    private $connection;
    private $classes = [];

    public function __construct(Connection $connection, $classes = [])
    {
        $this->connection = $connection;
        $this->classes = $classes;
    }

    public function getSchemaTool()
    {
        return new SchemaTool($this);
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    public function getClasses()
    {
        return $this->classes;
    }

} 