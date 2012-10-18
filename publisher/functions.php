<?php


function dump($data)
{
	if (is_array($data)) echo \Lemmon\Debugger::dumpArray($data);
	else                 echo \Lemmon\Debugger::dump($data);
}


function _t($phrase)
{
	return \Lemmon\I18n::t($phrase);
}