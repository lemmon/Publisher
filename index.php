<?php

// config
require 'config.php';

// bootstrap (part I)
require 'publisher/Bootstrap0.php';

// cache
require ROOT_DIR . '/cfg/cache.php';
$cache = new Cache;

// bootstrap (part II)
require 'publisher/Bootstrap1.php';

// run application
Application::run([
    'env'     => new Env,
    'db'      => new Db,
    'route'   => new Route,
    'cache'   => $cache,
]);
