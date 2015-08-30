<?php

/*
 * This file is part of JWT Laravel.
 *
 * (c) Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lucandrade\JwtAuth\Middleware;

use Closure;
use Lucandrade\JwtAuth\JwtAuth;
use Lucandrade\JwtAuth\Exceptions\MissingTokenException;
use Lucandrade\JwtAuth\Exceptions\InvalidTokenException;
use Lucandrade\JwtAuth\Exceptions\ExpiredTokenException;
use Lucandrade\JwtAuth\Exceptions\NotFoundTokenException;

/**
 * This is the check auth code request middleware class.
 *
 * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 */
class JwtCheckTokenMiddleware
{
    /**
     * The authorizer instance.
     *
     * @var \Lucandrade\JwtAuth\JwtAuth
     */
    protected $authorizer;

    /**
     * Create a new check auth code request middleware instance.
     *
     * @param \Lucandrade\JwtAuth\JwtAuth $authorizer
     */
    public function __construct(JwtAuth $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $this->authorizer->validateToken($request);
            $response = $next($request);
        } catch (\MissingTokenException $e) {
            $response = "Token is missing";
        } catch (\InvalidTokenException $e) {
            $response = "Invalid Token";
        } catch (\ExpiredTokenException $e) {
            $response = "This token has expired";
        } catch (\NotFoundTokenException $e) {
            $response = "Token not found";
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $response = empty($message) ? "Unexpected error" : $message;
        }
        return $response;
    }
}
