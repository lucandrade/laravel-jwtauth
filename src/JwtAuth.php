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

use Firebase\JWT\JWT;
use Lucandrade\JwtAuth\Excetions\MissingTokenException;
use Lucandrade\JwtAuth\Excetions\InvalidTokenException;
use Lucandrade\JwtAuth\Excetions\ExpiredTokenException;
use Illuminate\Http\Request;
use Config;

/**
 * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 */
class JwtAuth
{

    protected $jwt;

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  Firebase\JWT\JWT $jwt
     */
    public function __construct(JWT $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return array $config
     */
    public function getConfig()
    {
        return Config::get('jwtauth');
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  \Illuminate\Http\Request $request
     * @return boolean
     */
    public function validateToken($request)
    {
        $config = $this->getConfig();
        $checkOnlyHeader = $config['check_only_header'];
        if ($request->header('Authorization')) {
            return $this->checkToken($request->header('Authorization'));
        } else if (!$checkOnlyHeader && $request->input($config['get_param_key'])) {
            return $this->checkToken($request->input($config['get_param_key']));
        } else {
            throw new MissingTokenException;
        }
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  String $token
     * @return boolean
     */
    public function checkToken($token)
    {
        $payloadToken = $this->decodeToken($token);
        $storageToken = $this->getTokenFromStorage($payloadToken);
        if (!$this->isExpiredToken($storageToken)) {
            return true;
        } else {
            throw new ExpiredTokenException;
            
        }
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  array $storageToken
     * @return boolean
     */
    public function isExpiredToken($storageToken)
    {
        return $storageToken["expired_at"] > strtotime("now")
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  String $token
     * @return String $payloadToken
     */
    public function decodeToken($token)
    {
        $config = $this->getConfig();
        $decoded = JWT::decode($token, $config["key"], $config["arg"]);
        if ($decoded) {
            if (array_key_exists("token", $decoded)) {
                return $decoded["token"];
            } else {
                throw new InvalidTokenException;
            }
        } else {
            throw new InvalidTokenException;
        }
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  String $token
     * @return boolean
     */
    public function getTokenFromStorage($token)
    {

    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  array $payload
     * @return String
     */
    public function createToken(array $payload)
    {

    }
}
