<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Granula;

use Doctrine\DBAL\Connection;

class Query
{
    private $sql;
    private $params = [];
    private $types = [];

    final private function __construct($sql)
    {
        $this->sql = $sql;
    }

    public static function create($sql)
    {
        return new self($sql);
    }

    public function params($params)
    {
        $this->params = $params;
        return $this;
    }

    public function types($types)
    {
        $this->types = $types;
        return $this;
    }

    public function run(Connection $conn)
    {
        return $conn->executeQuery($this->sql, $this->params, $this->types);
    }
} 