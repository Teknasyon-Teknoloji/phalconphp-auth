# Installation

Add repository to composer
```
"repositories": [
    {
      "type": "package",
      "package": {
        "name": "teknasyon/phalconphp-auth",
        "version": "master",
        "source": {
          "url": "git@github.com:Teknasyon-Teknoloji/phalconphp-auth.git",
          "type": "git",
          "reference": "master"
        }
      }
    }
  ]
```

# Service Registration

```
$config = [
        'driver' => 'session', 
        'drivers' => [
         'session' => [
             'provider' => [
                'type' => 'phalcon.model',
                'model => '\App\Models\Users'
             ]
         ]
        ]
 ];
 
$di->setShared('auth', new Teknasyon\Phalcon\Auth\AuthService($config) );

```

#Usage 

### Login with credentials.
```
$credentials = ['username' => 'ilyas', 'password' => '12345'];
$result = $dependencyInjector->auth->attempt($credentials);

```

### Check auth status 
```
var_dump($di->auth->check()) // dumps true if a user is logged in. False otherwise.
```

### Logout
```
$di->auth->logout();
```

### Login via user object.
/!\ User object must implement `\Teknasyon\Interfaces\Auth\User`. 
```
$user = Users::findFirstById(1);

$di->auth->login($user); // 

var_dump($di->auth->check()) // outputs true. 

var_dump($di->auth->user()) // dumps the logged in user. 

```



## TODO 

- Remember me feature
- Token driver (create a separate table?)
- Session expiry time? 
- Make identifier column and password column names configurable. 