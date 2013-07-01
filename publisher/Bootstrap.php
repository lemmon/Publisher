<?php

//
// error reporitng
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

//
// start sessions
session_start();

//
// include general stuff
require ROOT_DIR . '/functions.php';
require LIBS_DIR . '/Lemmon/Autoloader.php';
require LIBS_DIR . '/Lemmon/Framework.php';

//
// autoloader
$loader = new Lemmon\Autoloader;
$loader->addMask('*_Controller', function($class){
    return USER_DIR . '/app/controllers/' . strtolower(str_replace('__', DIRECTORY_SEPARATOR, preg_replace('/(.)([A-Z])/u', '$1_$2', substr($class, 0, -11)))) . '_controller.php';
});
$loader->add(USER_DIR . '/app/models/$file.php');
$loader->add(USER_DIR . '/app/services/$file.php');
$loader = Lemmon\Framework::autoloader($loader);
$loader->add('$root/app/services/$file.php');
$loader->register(Lemmon\Autoloader::INCLUDE_PSR0);

//
// cache
Lemmon_Cache::setBase('/../cache');

//
// configuration
require ROOT_DIR . '/cfg/env.php';
require ROOT_DIR . '/cfg/db.php';
require ROOT_DIR . '/cfg/route.php';

//
// error handling
\Lemmon\Debugger::init();
\Lemmon\Environment::isDev(true);
