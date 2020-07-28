<?php

namespace Config;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;


class Database {

    public function __construct()
    {
        $dataBase = 'veterinaria';
        $username = 'root';
        $password = '';

        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => $dataBase,
            'username'  => $username,
            'password'  => $password,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setEventDispatcher(new Dispatcher(new Container));

        $capsule->setAsGlobal();
        
        $capsule->bootEloquent();
    }

}

?>