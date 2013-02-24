<?php

require __DIR__ . '/../config.php';
require __DIR__ . '/../publisher/Bootstrap.php';

$kr = explode("\n", trim(file_get_contents(__DIR__ . '/../user/app/models/sk_kraje.txt')));
$ok = explode("\n", trim(file_get_contents(__DIR__ . '/../user/app/models/sk_okresy.txt')));
$ob = explode("\n", trim(file_get_contents(__DIR__ . '/../user/app/models/sk_obce.txt')));

// kraje
foreach ($kr as $_kr)
{
	$_kr = explode("\t", $_kr);
	preg_match_all("/\n({$_kr[1]}\t[^\n]*)/", "\n" . join("\n", $ok), $m_ok);
	$data_ok = [];
	// okresy
	foreach ($m_ok[1] as $_ok)
	{
		$_ok = explode("\t", $_ok);
		preg_match_all("/\n({$_ok[1]}\t[^\n]*)/", "\n" . join("\n", $ob), $m_ob);
		$data_ob = [];
		// obce
		foreach ($m_ob[1] as $_ob)
		{
			$_ob = explode("\t", $_ob);
			$data_ob[$_ob[1]] = $_ob[2];
		}
		//
		$data_ok[$_ok[1]] = [
			'id'   => $_ok[1],
			'name' => $_ok[2],
			'data' => $data_ob,
		];
	}
	//
	$data[$_kr[1]] = [
		'id'   => $_kr[1],
		'name' => $_kr[2],
		'data' => $data_ok,
	];
}

header('Content-type: text/xml; charset=utf-8');

?><?xml version="1.0" encoding="UTF-8" ?>
<country name="Slovenská republika">
	<?php foreach ($data as $region): ?>
	<region id="<?= $region['id'] ?>" name="<?= $region['name'] ?>" slug="<?= Lemmon\String::asciize($region['name']) ?>">
		<?php foreach ($region['data'] as $district): ?>
		<district id="<?= $district['id'] ?>" name="<?= $district['name'] ?>" slug="<?= Lemmon\String::asciize($district['name']) ?>">
			<?php foreach ($district['data'] as $_id => $_name): ?>
			<municipality id="<?= $_id ?>" name="<?= $_name ?>" slug="<?= Lemmon\String::asciize($_name) ?>" />
			<?php endforeach ?>
		</district>
		<?php endforeach ?>
	</region>
	<?php endforeach ?>
</country>