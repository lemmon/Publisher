<?php

// error reporitng
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE/* & ~E_STRICT*/);
#error_reporting(E_ALL | E_STRICT);

// start sessions
session_start();

// define dirs
define('ROOT_DIR', __DIR__);
define('LIBS_DIR', BASE_DIR . (is_dir(BASE_DIR . '/lib') ? '/lib' : '/../lib2'));

// include general stuff
require LIBS_DIR . '/Lemmon/Autoloader.php';
require LIBS_DIR . '/Lemmon/Framework.php';
require LIBS_DIR . '/Lemmon/functions.php';

// autoloader
$loader = Lemmon\Framework::autoloader(new Lemmon\Autoloader);
$loader->register(Lemmon\Autoloader::INCLUDE_PSR0);

// cache
Lemmon_Cache::setBase('/../cache');

// configuration
require ROOT_DIR . '/cfg/env.php';
require ROOT_DIR . '/cfg/db.php';
require ROOT_DIR . '/cfg/route.php';
require ROOT_DIR . '/cfg/mailer.php';
