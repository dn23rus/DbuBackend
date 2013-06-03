DbuBackend module

Insert in your config/autoload/local.php

    'dbu-backend' => array(
        'users' => array(
            'admin' => array(
                'login'     => 'admin',
                'password'  => 'admin123',
                'hashed'    => false,
            )
        )
    ),
