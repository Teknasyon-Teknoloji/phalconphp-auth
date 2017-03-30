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
    protected $customUserManagers = [];

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

        if (isset($this->drivers[$driverName])) {
            // prepare config
            $driverConfig = $this->config['drivers'][$driverName] ?? [];
            $defaultConfig = $this->config['defaults'] ?? [];
            foreach ($defaultConfig as $configKey => $configVal) {
                if(!isset($driverConfig[$configKey])) {
                    $driverConfig[$configKey] = $configVal;
                }
            }

            // resolve instance and return
            return new $this->drivers[$driverName](
                $driverConfig,
                $this->resolveUserManager($driverConfig['userManager']['type'], $driverConfig['userManager']['options']),
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
                if (isset($this->customUserManagers[$type])) {
                    $userManager = call_user_func($this->customUserManagers[$type], $options);
                    if (!($userManager instanceof UserManager)) {
                        throw new \Exception('Custom user manager must implement UserManager interface. ');
                    }
                    return $userManager;
                }
                throw new \InvalidArgumentException('Invalid user provider type given. ' . $this);
        }
    }
}