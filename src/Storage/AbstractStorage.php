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

use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Config;

/**
 * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 */
class AbstractStorage
{

    protected $resolver;
    protected $connectionName;
    protected $conifg;

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  Firebase\JWT\JWT $jwt
     */
    public function __construct(Resolver $resolver)
    {
        $this->config = Config::get('jwtauth');
        $this
            ->setResolver($resolver)
            ->setConnectionName($this->config['connection']);
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  Resolver
     * @return  $this
     */
    public function setResolver(Resolver $resolver)
    {
        $this->resolver = $resolver;
        return $this;
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return Resolver $this->resolver
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  String $connectionName
     * @return  $this
     */
    public function setConnectionName($connectionName)
    {
        $this->connectionName = $connectionName;
        return $this;
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return \Illuminate\Database\ConnectionInterface
     */
    protected function getConnection()
    {
        return $this->resolver->connection($this->connectionName);
    }
}
