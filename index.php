<?php

// define root dir
define('BASE_DIR', __DIR__);

// config
require 'config.php';

// bootstrap
require 'vendor/bootstrap.php';

// run application
Application::run([
	'env'    => new Env,
	'db'     => new Db,
	'route'  => new Route,
	'mailer' => new Mailer,
]);
