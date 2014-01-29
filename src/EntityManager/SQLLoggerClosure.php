<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula\EntityManager;

use Doctrine\DBAL\Logging\SQLLogger;

class SQLLoggerClosure implements SQLLogger
{
    /**
     * @var \Closure
     */
    private $closure;

    public function __construct(\Closure $callback)
    {
        $this->closure = $callback;
    }


    public function startQuery($sql, array $params = null, array $types = null)
    {
        $call = $this->closure;
        $call($sql, $params, $types);
    }

    public function stopQuery()
    {
    }
}