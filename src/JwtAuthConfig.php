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

use Config;

/**
 * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 */
class JwtAuthConfig
{

    public static function get()
    {
        $config = Config::get('jwtauth');
        if (!empty($config)) {
            return $config;
        } else {
            throw new \Exception("Configuration file not found");
        }
    }
}
