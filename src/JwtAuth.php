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
use Lucandrade\JwtAuth\Exceptions\MissingTokenException;
use Lucandrade\JwtAuth\Exceptions\InvalidTokenException;
use Lucandrade\JwtAuth\Exceptions\ExpiredTokenException;
use Lucandrade\JwtAuth\Exceptions\NotFoundTokenException;
use Lucandrade\JwtAuth\Storage\SessionStorage;
use Illuminate\Http\Request;
use Config;

/**
 * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 */
class JwtAuth
{

    protected $jwt;
    protected $sessionStorage;

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
     * @return array void
     */
    public function setSessionStorage(SessionStorage $sessionStorage)
    {
        $this->sessionStorage = $sessionStorage;
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return array $config
     */
    public function getConfig()
    {
        $config = Config::get('jwtauth');
        if (!empty($config)) {
            return Config::get('jwtauth');
        } else {
            throw new Exception("Configuration file not found");
        }
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
        return $storageToken["expired_at"] > strtotime("now");
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
     * @return array
     */
    public function getTokenFromStorage($token)
    {
        $storageToken = $this->session->getToken($token);
        if ($storageToken !== false) {
            return $storageToken;
        } else {
            throw new NotFoundTokenException;
        }
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  array $payload
     * @return String
     */
    public function createToken(array $payload)
    {
        try {
            $userToken = $this->session->createToken(str_random(32));
            $tokenData = [
                "exp" => $userToken["expired_at"],
                "iat" => $userToken["created_at"],
                "token" => $userToken["token"],
                "data" => $payload
            ];
            return JWT::encode($tokenData, $this->config("key"));
        } catch (Exception $e) {
            return false;
        }
    }
}
