DbuBackend module

Insert in your config/autoload/local.php

    \DbuBackend\Module::BACKEND_ROOT_CONFIG_NAME => array(
        'users' => array(
            'admin' => array(
                'login'     => 'admin',
                'password'  => 'admin123',
                'hashed'    => false,
            )
        )
    ),

Version control follows [Semantic Versioning](http://semver.org/)
