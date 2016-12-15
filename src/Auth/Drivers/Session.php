<?php

namespace Teknasyon\Phalcon\Auth\Drivers;

use Phalcon\DiInterface;
use Phalcon\Http\CookieInterface;
use Phalcon\Session\AdapterInterface as SessionInterface;
use Teknasyon\Phalcon\Auth\Interfaces\AuthDriver;
use Teknasyon\Phalcon\Auth\Interfaces\User;
use Teknasyon\Phalcon\Auth\Interfaces\UserManager;
use Teknasyon\Phalcon\Auth\Interfaces\UserProvider;


/**
 * Class Session
 * @package Teknasyon\Phalcon\Auth\Drivers
 * @author Ilyas Serter <ilyasserter@teknasyon.com>
 */
class Session implements AuthDriver
{

    private $config;
    private $user;

    protected $userManager;
    protected $hashingService;
    protected $sessionHandler;
    protected $cookies;

    /**
     * Session constructor.
     * @param array $config
     * @param UserManager $userManager
     * @param DiInterface $di
     * @throws \Exception
     * @internal param UserProvider $userManager
     */
    public function __construct(array $config, UserManager $userManager, DiInterface $di)
    {
        $this->config = $config;
        $this->userManager = $userManager;

        // get session handler
        $this->sessionHandler = $di->get($config['sessionServiceName'] ?? 'session');
        // validate session handler
        if( ! $this->sessionHandler instanceof SessionInterface) {
            throw new \Exception('Session service cannot be resolved from the DI container.');
        }

        // get hashing service
        $this->hashingService = $di->get($config['hashingServiceName'] ?? 'security');

        // validate hashing service
        if( !is_object($this->hashingService)
            || !method_exists($this->hashingService,'hash')
            || !method_exists($this->hashingService,'checkHash')
        ) {
            throw new \Exception('Hashing (security) service cannot be resolved from the DI container.');
        }

        // get cookies service
        $this->cookies = $di->get($config['cookiesServiceName'] ?? 'cookies');

        // validate cookie service
        if( ! $this->cookies instanceof CookieInterface) {
            throw new \Exception('Cookies service cannot be resolved from the DI container.');
        }
    }

    /**
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function attempt(array $credentials = [],bool $remember = false) : bool
    {
        $user = $this->userManager->findUserByCredentials($credentials);
        if(!$user) {
            return false;
        } else if($this->hashingService->checkHash($credentials['password'],$user->getPassword())) {
            return $this->login($user,$remember);
        }

        return false;
    }

    /**
     * @return bool
     */
    public function check() : bool
    {
        return $this->sessionHandler->has('teknasyon_login') && $this->user();
    }

    /**
     * @param bool $fresh
     * @return User|null
     */
    public function user($fresh = false)
    {
        if($fresh || !$this->user) {
            $this->user = $this->userManager->findUserById($this->sessionHandler->get('teknasyon_login'));
        }

        return $this->user;
    }

    /**
     * @param User $user
     * @param bool $remember
     * @return bool
     */
    public function login(User $user,bool $remember = false)
    {
        if($remember) {
            $this->cookies->set('auth_token',md5(microtime(1)));
            $this->userManager->updateAuthToken($user,$this->cookies->get('auth_token'));
        }

        $this->sessionHandler->set('teknasyon_login',$user->getId());

        return !is_null($this->user());
    }

    /**
     *
     */
    public function logout()
    {
        $this->user = null;
        $this->sessionHandler->remove('teknasyon_login');
    }


}