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
use Lucandrade\JwtAuth\JwtAuthConfig;
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
    private $payload;

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  Firebase\JWT\JWT $jwt
     */
    public function __construct(JWT $jwt, SessionStorage $sessionStorage)
    {
        $this->jwt = $jwt;
        $this->sessionStorage = $sessionStorage;
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  $payload
     * @return  JwtAuth [this]
     */
    private function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
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
        return JwtAuthConfig::get();
    }

    public function getTokenFromRequest(\Illuminate\Http\Request $request)
    {
        $config = $this->getConfig();
        $checkOnlyHeader = $config['check_only_header'];
        if ($request->header('Authorization')) {
            return $request->header('Authorization');
        } else if (!$checkOnlyHeader && $request->input($config['get_param_key'])) {
            return $request->input($config['get_param_key']);
        } else {
            throw new MissingTokenException;
        }
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  \Illuminate\Http\Request $request
     * @return boolean
     */
    public function validateToken(\Illuminate\Http\Request $request)
    {
        return $this->checkToken($this->getTokenFromRequest($request));
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
        $expired = $this->isExpiredToken($storageToken);
        if (!$expired) {
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
        return $storageToken["expired_at"] < strtotime("now");
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  String $token
     * @return String $payloadToken
     */
    public function decodeToken($token)
    {
        $config = $this->getConfig();
        $decoded = (array) JWT::decode($token, $config["key"], array($config["alg"]));
        if ($decoded) {
            if (array_key_exists("token", $decoded) && array_key_exists("data", $decoded)) {
                $this->setPayload($decoded["data"]);
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
        $storageToken = $this->sessionStorage->getToken($token);
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
            $config = $this->getConfig();
            $userToken = $this->sessionStorage->createToken(str_random(32));
            $tokenData = [
                "exp" => $userToken["expired_at"],
                "iat" => $userToken["created_at"],
                "token" => $userToken["token"],
                "data" => $payload
            ];
            return JWT::encode($tokenData, $config["key"]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
