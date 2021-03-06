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

use Lucandrade\JwtAuth\Storage\AbstractStorage;
use Config;

/**
 * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
 */
class SessionStorage extends AbstractStorage
{

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @return String
     */
    public function getTableName()
    {
        return $this->config["tbl_session_name"];
    }
    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  String $token
     * @return array
     */
    public function getToken($token)
    {
        $storageToken = $this
            ->getConnection()
            ->table($this->getTableName())
            ->where("token", $token)
            ->where("active", true);
        if ($storageToken->count() > 0) {
            return (array) $storageToken->first();
        } else {
            return false;
        }
    }

    /**
     * @author Lucas Andrade <lucas.andrade.oliveira@hotmail.com>
     * @param  String $token
     * @param  Array $payload
     * @param  Integer $userId
     * @return array
     */
    public function createToken($token, array $payload, $userId)
    {
        $return = false;
        $data = [
            "token" => $token,
            "user_id" => $userId,
            "payload" => json_encode($payload),
            "expired_at" => strtotime("+1 day"),
            "created_at" => strtotime("now")
        ];
        $id = $this
            ->getConnection()
            ->table($this->getTableName())
            ->insertGetId($data);
        return (array) $this
            ->getConnection()
            ->table($this->getTableName())
            ->where('id', $id)
            ->first();
    }
}
