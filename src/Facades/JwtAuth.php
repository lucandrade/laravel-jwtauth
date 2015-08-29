<?php

/*
 * This file is part of JWT Laravel.
 *
 * (c) Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lucandrade\JwtAuth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * This is the jwtauth facade class.
 *
 * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 */
class JwtAuth extends Facade
{
    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return String
     */
    protected static function getFacadeAccessor()
    {
        return 'jwtauth';
    }
}
