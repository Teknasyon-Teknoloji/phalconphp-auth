<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 21/11/2016
 * Time: 11:36
 */

namespace Teknasyon\Phalcon\Auth;

use Phalcon\Di\InjectionAwareInterface;
use Teknasyon\Phalcon\Auth\Interfaces\UserManager;
use Teknasyon\Phalcon\Auth\UserManagers\PhalconDb;
use Teknasyon\Phalcon\Auth\UserManagers\PhalconModel;
use Teknasyon\Phalcon\InjectionAwareness;
use Teknasyon\Phalcon\Auth\Drivers\Session as SessionDriver;
use Teknasyon\Phalcon\Auth\Drivers\Token as TokenDriver;
use Teknasyon\Phalcon\Auth\Interfaces\AuthDriver;
use Teknasyon\Phalcon\Auth\Interfaces\UserProvider;
use Teknasyon\Phalcon\Auth\Providers\Phalcon\DbUserProvider;
use Teknasyon\Phalcon\Auth\Providers\Phalcon\ModelUserProvider;

/**
 * Class AuthService
 * @package Teknasyon\Phalcon\Auth
 * @author Ilyas Serter <ilyasserter@teknasyon.com>
 */
class AuthService implements InjectionAwareInterface
{

    use InjectionAwareness;

    protected $config;
    protected $driverInstances = [];
    protected $drivers = [
        'session' => SessionDriver::class,
        'token' => TokenDriver::class
    ];
    protected $customUserProviders = [];

    /**
     * AuthService constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $driverName
     * @param AuthDriver $driver
     * @return $this
     */
    public function extend($driverName, AuthDriver $driver)
    {
        $this->drivers[$driverName] = $driver;

        return $this;
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->drive()->{$method}(...$parameters);
    }

    /**
     * @param null $driverName
     * @return mixed
     */
    public function drive($driverName = null)
    {
        $driverName = $driverName ?: $this->config['driver'];

        return isset($this->driverInstances[$driverName])
            ? $this->driverInstances[$driverName]
            : $this->driverInstances[$driverName] = $this->resolveDriver($driverName);
    }

    /**
     * @param $driverName
     * @return mixed
     */
    protected function resolveDriver($driverName)
    {

        $config = $this->config['drivers'][$driverName] ?? [];

        if (isset($this->drivers[$driverName])) {
            return new $this->drivers[$driverName](
                $config,
                $this->resolveUserManager($config['userManager']['type'], $config['userManager']['options']),
                $this->getDi()
            );
        }

        throw new \InvalidArgumentException('Auth driver not found: ' . $driverName);
    }

    /**
     * @param $type
     * @param array $options
     * @return mixed|PhalconDb|PhalconModel
     * @throws \Exception
     */
    protected function resolveUserManager($type, array $options = [])
    {

        switch ($type) {
            case 'phalcon.model':
                return new PhalconModel($options);
                break;
            case 'phalcon.pdo';
                return new PhalconDb($this->getDi()->get('db'), $options); // @TODO get pdo service name from config.
                break;
            default:
                if (isset($this->customUserProviders[$type])) {
                    $userProvider = call_user_func($this->customUserProviders[$type], $options);
                    if (!($userProvider instanceof UserManager)) {
                        throw new \Exception('Custom user provider must implement UserProvider interface. ');
                    }
                    return $userProvider;
                }
                throw new \InvalidArgumentException('Invalid user provider type given. ' . $this);
        }
    }
}