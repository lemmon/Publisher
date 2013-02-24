<?php

require __DIR__ . '/../config.php';
require __DIR__ . '/../publisher/Bootstrap.php';

$xml = new SimpleXMLElement(file_get_contents(USER_DIR . '/data/sk.xml'));

foreach ($xml->xpath("//district[contains(@slug, 'kosice')]") as $item)
{
	echo $item['id'], ': ', $item['name'], '<br>';
}
foreach ($xml->xpath("//municipality[contains(@slug, 'kosice')]") as $item)
{
	echo $item['id'], ': ', $item['name'], '<br>';
}

die('--ok');