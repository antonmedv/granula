<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Granula\EntityManager\EntityManagerEventArgs;
use Granula\EntityManager\Events;

class EntityManager
{
    /**
     * @var EntityManager
     */
    private static $current;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var array
     */
    private $classes = [];

    /**
     * @var bool
     */
    private $dev;

    /**
     * @param array $params
     * @param array $classes
     */
    public function __construct(array $params, $classes = [])
    {
        if (isset($params['dev'])) {
            $this->dev = $params['dev'];
            unset($params['dev']);
        }

        if (isset($params['cache'])) {
            $this->cache = $params['cache'];
            unset($params['cache']);
        } else {
            $this->cache = new ArrayCache();
        }

        if (isset($params['event_manager'])) {
            $this->eventManager = $params['event_manager'];
            unset($params['event_manager']);
        } else {
            $this->eventManager = new EventManager();
        }

        if (isset($params['connection'])) {
            $connection = $params['connection'];
            unset($params['connection']);
        } else {
            $config = new Configuration();
            $connection = DriverManager::getConnection($params, $config, $this->eventManager);
        }

        $this->connection = $connection;
        $this->classes = $classes;

        self::setInstance($this);

        if ($this->dev) {
            $this->eventManager->dispatchEvent(Events::preUpdateSchema, new EntityManagerEventArgs($this));

            $this->getSchemaTool()->updateSchema();

            $this->eventManager->dispatchEvent(Events::postUpdateSchema, new EntityManagerEventArgs($this));
        }
    }

    /**
     * @return EntityManager
     */
    public static function getInstance()
    {
        return self::$current;
    }

    /**
     * @param EntityManager $next
     */
    public static function setInstance(EntityManager $next)
    {
        self::$current = $next;
    }

    /**
     * @return SchemaTool
     */
    public function getSchemaTool()
    {
        static $schemaTool = null;

        if (null === $schemaTool) {
            $schemaTool = new SchemaTool($this);
        }

        return $schemaTool;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param string $class Class name
     * @return Meta
     */
    public function getMetaForClass($class)
    {
        $meta = new Meta();
        $class::describe($meta);

        return $meta;
    }

    /**
     * @return Meta[]
     */
    public function getMetaForAllClasses()
    {
        foreach ($this->classes as $class) {
            yield $this->getMetaForClass($class);
        }
    }

} 