<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 24/11/2016
 * Time: 08:28
 */

namespace Teknasyon\Phalcon\Auth;


use Teknasyon\Phalcon\Auth\Interfaces\User as AuthUser;


class User implements AuthUser
{

    protected $attributes;
    protected $identifierColumn = 'id';
    protected $passwordColumn = 'password';
    protected $tokenColumn = 'auth_token';


    /**
     * User constructor.
     * @param array $attributes
     * @param $identifierColumn
     * @param $passwordColumn
     * @param $tokenColumn
     */
    public function __construct(array $attributes, $identifierColumn, $passwordColumn,$tokenColumn)
    {
        $this->attributes = $attributes;
        $this->identifierColumn = $identifierColumn;
        $this->passwordColumn = $passwordColumn;
        $this->tokenColumn = $tokenColumn;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->attributes[$this->identifierColumn];
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->attributes[$this->passwordColumn];
    }

    public function getAuthToken()
    {
        return $this->attributes[$this->tokenColumn];
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function __get($key)
    {
        return $this->attributes[$key];
    }


    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

}