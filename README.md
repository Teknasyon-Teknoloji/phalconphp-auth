# Introduction
An authentication package for PhalconPHP. It provides session-based authentication with an easy-to-use API. 

The architecture is designed to support multiple drivers, and implementing a token-based authentication driver is next on my list. 
Grab a fork! ;) 

# Installation via Composer

```
composer require teknasyon/phalconphp-auth
```

# Service Registration

You may register the service with default settings
``` 
$di->setShared('auth', new Teknasyon\Phalcon\Auth\AuthService() );

```
Or you may pass-in a configuration array like below. 

``` 
$config = [
          'driver' => 'session', 
          'userManager' => [
              'type' => 'phalcon.model',
              'model => '\App\Models\Users'
           ]
          ];
$di->setShared('auth', new Teknasyon\Phalcon\Auth\AuthService() );

```
This service is highly configurable. See stubs/config.php for more options. 

# Usage 

### Login with credentials.
```
$credentials = ['username' => 'ilyas', 'password' => '12345'];
$result = $di->auth->attempt($credentials);  // returns true on success, false on failure. 

```

### Check auth status 
```
var_dump($di->auth->check()) // dumps true if a user is logged in. False otherwise.
```

### Logout
```
$di->auth->logout();
```

### Login via user model.

/!\ Users model must implement `\Teknasyon\Phalcon\Auth\Interfaces\User`. 

```
$user = Users::findFirstById(1);

$di->auth->login($user); // 

var_dump($di->auth->check()) // outputs true. 

var_dump($di->auth->user()) // dumps the logged in user. 

```



## TODO 
- Develop feature to expire authentications after n seconds. Currently, it's dependent on the session expire time. 
- Develop a token driver that utilizes JWT. 
