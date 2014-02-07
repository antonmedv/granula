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
use Doctrine\DBAL\Logging\SQLLogger;
use Granula\EntityManager\EntityManagerEventArgs;
use Granula\EntityManager\Events;
use Granula\EntityManager\SQLLoggerClosure;

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
    private $dev = false;

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

        if (isset($params['connection'])) {
            $connection = $params['connection'];
            unset($params['connection']);
        } else {
            $config = new Configuration();

            if (isset($params['sql_logger'])) {

                if ($params['sql_logger'] instanceof \Closure) {
                    $config->setSQLLogger(new SQLLoggerClosure($params['sql_logger']));
                } else if ($params['sql_logger'] instanceof SQLLogger) {
                    $config->setSQLLogger($params['sql_logger']);
                }

                unset($params['sql_logger']);
            }

            if (isset($params['event_manager'])) {
                $eventManager = $params['event_manager'];
                unset($params['event_manager']);
            } else {
                $eventManager = new EventManager();
            }

            $connection = DriverManager::getConnection($params, $config, $eventManager);
        }

        $this->connection = $connection;
        $this->classes = $classes;

        self::setInstance($this);

        if ($this->dev) {
            $this->getEventManager()->dispatchEvent(Events::preUpdateSchema, new EntityManagerEventArgs($this));

            $this->getSchemaTool()->updateSchema();

            $this->getEventManager()->dispatchEvent(Events::postUpdateSchema, new EntityManagerEventArgs($this));
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
     * @return EventManager
     */
    public function getEventManager()
    {
        return $this->connection->getEventManager();
    }

    /**
     * @param string $class Class name
     * @return Meta
     */
    public function getMetaForClass($class)
    {
        $meta = new Meta($class);
        $class::describe($meta);

        return $meta;
    }

    /**
     * @return Meta[]
     */
    public function getMetaForAllClasses()
    {
        foreach ($this->classes as $class) {
            yield $class => $this->getMetaForClass($class);
        }
    }

} 