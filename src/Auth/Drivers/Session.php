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

    protected $key;
    protected $userManager;
    protected $hashingService;
    protected $sessionHandler;
    protected $cookies;
    protected $rememberCookieName;

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

        // set remember cookie name
        if(isset($config['rememberCookieName'])) {
            $this->rememberCookieName = $config['rememberCookieName'];
        } else {
            $this->rememberCookieName = 'remember_' . md5(static::class);
        }

        // set session key
        if(isset($config['sessionKey'])) {
            $this->key = $config['sessionKey'];
        } else {
            $this->key = 'auth_' . md5(static::class);
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
        return ($this->sessionHandler->has($this->key) && $this->user()) || $this->remember();
    }

    /**
     * @param bool $fresh
     * @return User|null
     */
    public function user($fresh = false)
    {
        if($fresh || !$this->user) {
            $this->user = $this->userManager->findUserById($this->sessionHandler->get($this->key));
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
            $token = md5(microtime(1) . '_salt_' . $this->key);
            $this->userManager->updateAuthToken($user,$token);
            $this->cookies->set($this->rememberCookieName, $user->getId() . '|' . $token);
        }

        $this->sessionHandler->set($this->key,$user->getId());

        return !is_null($this->user());
    }

    public function remember()
    {
        $remember = $this->cookies->get($this->rememberCookieName);
        if(!empty($remember) && is_string($remember) && strstr($remember,'|') !== false) {
            $data = explode('|',$remember); // 0=>userId, 1=>authToken
            $user = $this->userManager->findUserById((int) $data[0]);
            if($user->getAuthToken() == $data[1]) {
                $this->user = $user;
                $this->sessionHandler->set($this->key,$user->getId());
                return true;
            }
        }

        return false;
    }

    /**
     *
     */
    public function logout()
    {
        $this->user = null;
        $this->sessionHandler->remove($this->key);
    }


}