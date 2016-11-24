<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 21/11/2016
 * Time: 10:33
 */

namespace Teknasyon\Phalcon\Auth\Drivers;


use Phalcon\DiInterface;
use Teknasyon\Interfaces\Auth\AuthDriver;
use Teknasyon\Interfaces\Auth\User;
use Teknasyon\Interfaces\Auth\UserProvider;


class Token implements AuthDriver
{


    private $user;
    /**
     * @var array
     */
    private $config;
    /**
     * @var UserProvider
     */
    private $userProvider;
    private $requestService;
    private $hashingService;

    public function __construct(array $config, UserProvider $userProvider, DiInterface $di)
    {

        $this->config = $config;
        $this->userProvider = $userProvider;
        $this->requestService = $di->get($config['requestServiceName'] ?? 'request');
        $this->hashingService = $di->get($config['hashingServiceName'] ?? 'security');
    }

    /**
     * @TODO remove this method from the interface??
     *
     * @param array $credentials
     * @return bool
     */
    public function attempt(array $credentials = []) : bool
    {
        $user = $this->userProvider->findUserByCredentials($credentials);
        if(!$user) {
            return false;
        } else if($this->hashingService->checkHash($credentials['password'],$user->getPassword())) {
            return $this->login($user);
        }

        return false;
    }

    public function check() : bool
    {
        // TODO: Implement check() method.
    }

    public function user($fresh = false) : User
    {
        // @TODO get auth user
    }

    public function login(User $user)
    {
        return !is_null($this->user());
    }

    public function logout()
    {
        $this->user = null;

    }

}