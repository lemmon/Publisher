<?php

//
// include general stuff
require ROOT_DIR . '/functions.php';
require LIBS_DIR . '/Lemmon/Autoloader.php';
require LIBS_DIR . '/Lemmon/Framework.php';

//
// autoloader
$loader = new Lemmon\Autoloader;
$loader = Lemmon\Framework::autoloader($loader);
$loader->add('$root/app/services/$file.php');
$loader->register(Lemmon\Autoloader::INCLUDE_PSR0);

//
// i18n
$i18n = new \Lemmon\I18n\I18n;

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
