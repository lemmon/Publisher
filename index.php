<?php

// config
require 'config.php';

// bootstrap
require 'publisher/Bootstrap.php';

// run application
Application::run([
    'env'     => new Env,
    'db'      => new Db,
    'route'   => new Route,
]);
