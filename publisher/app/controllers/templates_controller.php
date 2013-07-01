<?php
/**
* 
*/
class Templates_Controller extends AbstractFrontend_Controller
{


    function css()
    {
        $this->_cssParse(USER_DIR . '/' . $this->route->fileBase . '.less');
    }


    function cssBase()
    {
        $this->_cssParse(BASE_DIR . dirname($this->route->getSelf()) . '/' . substr(basename($this->route->getSelf()), 0, -4) . '.less');
    }


    private function _cssParse($file)
    {
        // less css
        require_once LIBS_DIR . '/lessphp/lessc.inc.php';
        $less = new lessc($file);
        // parse
        $code = $less->parse();
        // return
        header('Content-type: text/css; charset=utf-8');
        echo $code;
        exit;
    }
}
