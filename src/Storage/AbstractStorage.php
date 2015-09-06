<?php

/*
 * This file is part of JWT Laravel.
 *
 * (c) Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lucandrade\JwtAuth\Storage;

use Lucandrade\JwtAuth\JwtAuthConfig;
use Illuminate\Database\DatabaseManager;
use Config;

/**
 * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 */
class AbstractStorage
{

    protected $databaseManager;
    protected $connectionName;
    protected $config;

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  Illuminate\Database\DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->config = JwtAuthConfig::get();
        $this
            ->setDatabaseManager($databaseManager)
            ->setConnectionName($this->config['connection']);
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  Illuminate\Database\DatabaseManager
     * @return  AbstractStorage
     */
    public function setDatabaseManager(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
        return $this;
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return Illuminate\Database\DatabaseManager
     */
    public function getDatabaseManager()
    {
        return $this->databaseManager;
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  String $connectionName
     * @return  AbstractStorage
     */
    public function setConnectionName($connectionName)
    {
        $this->connectionName = $connectionName;
        return $this;
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return Illuminate\Database\DatabaseManager
     */
    protected function getConnection()
    {
        return $this->getDatabaseManager()->connection($this->connectionName);
    }
}
