<?php

//
// debugging
function dump($data) {
    if (is_array($data))
        echo \Lemmon\Debugger::dumpArray($data);
    else
        echo \Lemmon\Debugger::dump($data);
}

//
// i18n
function _t($s) {
    global $i18n;
    return call_user_func_array([$i18n, 't'], func_get_args());
}
