<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 21/11/2016
 * Time: 10:33
 */

namespace Teknasyon\Phalcon\Auth\Drivers;


use Phalcon\DiInterface;
use Teknasyon\Phalcon\Auth\Interfaces\AuthDriver;
use Teknasyon\Phalcon\Auth\Interfaces\User;
use Teknasyon\Phalcon\Auth\Interfaces\UserManager;


class Token implements AuthDriver
{


    private $user;
    /**
     * @var array
     */
    private $config;
    private $userManager;
    private $requestService;
    private $hashingService;

    public function __construct(array $config, UserManager $userManager, DiInterface $di)
    {

        $this->config = $config;
        $this->userManager = $userManager;
        $this->requestService = $di->get($config['requestServiceName'] ?? 'request');
        $this->hashingService = $di->get($config['hashingServiceName'] ?? 'security');
    }

    /**
     *
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function attempt(array $credentials = [], bool $remember = false) : bool
    {
        $user = $this->userManager->findUserByCredentials($credentials);
        if(!$user) {
            return false;
        } else if($this->hashingService->checkHash($credentials['password'],$user->getPassword())) {
            return $this->login($user,$remember);
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

    public function login(User $user, bool $remember = false)
    {
        return !is_null($this->user());
    }

    public function logout()
    {
        $this->user = null;

    }

}