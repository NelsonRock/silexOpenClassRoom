<?php

namespace silex\DAO;

use Doctrine\DBAL\Connection;

abstract class DAO
{
    /**
    * Database Connection
    * @var \Doctrine\DBAL\Connection
    */
    private $db;

    /**
    * Constructor
    * @param \Doctrine\DBAL\Connection the database connection object
    */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    protected function getDb()
    {
        return $this->db;
    }

    /**
     * Create a Domain Object based on a DB <row class=""></row>
     *
     * 
     */
    protected abstract function buildDomainObject(array $row);

}