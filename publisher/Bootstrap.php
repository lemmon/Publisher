<?php

// error reporitng
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

// start sessions
session_start();

// include general stuff
require ROOT_DIR . '/functions.php';
require LIBS_DIR . '/Lemmon/Autoloader.php';
require LIBS_DIR . '/Lemmon/Framework.php';

// autoloader
$loader = Lemmon\Framework::autoloader(new Lemmon\Autoloader);
$loader->add('$root/app/services/$file.php');
$loader->register(Lemmon\Autoloader::INCLUDE_PSR0);

// cache
Lemmon_Cache::setBase('/../cache');

// configuration
require ROOT_DIR . '/cfg/env.php';
require ROOT_DIR . '/cfg/db.php';
require ROOT_DIR . '/cfg/route.php';

// error handling
\Lemmon\Debugger::init();
\Lemmon\Environment::isDev(true);
