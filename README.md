# Installation

```
composer require teknasyon/phalconphp-auth
```

# Service Registration

```
$config = [
        'driver' => 'session', 
        'drivers' => [
         'session' => [
             'userProvider' => [
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
- add LICENSE file.
- Remember me feature
- Token driver (create a separate table?)
- Session expiry time? 
- Make identifier column and password column names configurable. 