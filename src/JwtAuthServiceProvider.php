<?php

/*
 * This file is part of JWT Laravel.
 *
 * (c) Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lucandrade\JwtAuth;

use Lucandrade\JwtAuth\JwtAuth;
use Lucandrade\JwtAuth\Storage\SessionStorage;
use Firebase\JWT\JWT;
use Illuminate\Support\ServiceProvider;

class JwtAuthServiceProvider extends ServiceProvider
{
    
    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
        $this->setupMigrations();
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/jwtauth.php');
        $this->publishes([$source => config_path('jwtauth.php')], 'config');
        $this->mergeConfigFrom($source, 'jwtauth');
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return void
     */
    protected function setupMigrations()
    {
        $source = realpath(__DIR__.'/../database/migrations/');
        $this->publishes([$source => database_path('migrations')], 'migrations');
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return void
     */
    public function register()
    {
        $this->registerAuthorizer();
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return void
     */
    public function registerAuthorizer()
    {
        $this->app->bindShared("jwtauth", function ($app) {
            $jwt = new JwtAuth(new JWT(), new SessionStorage($app["db"]));
            return $jwt;
        });
    }
}
