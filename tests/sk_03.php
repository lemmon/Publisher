<?php

require __DIR__ . '/../config.php';
require __DIR__ . '/../publisher/Bootstrap.php';

$xml = new SimpleXMLElement(file_get_contents(USER_DIR . '/data/sk.xml'));

dump([
	(string)$xml->xpath('//municipality[@id=544221]')[0]['name'],
	(string)$xml->xpath('//district[@id=800]')[0]['name'],
]);

die('--ok');