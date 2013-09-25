<?php
/**
* 
*/
class Templates_Controller extends AbstractFrontend_Controller
{


    function css()
    {
        if ($file = USER_DIR . '/' . $this->route->fileBase . '.less' and file_exists($file)) {
            // ok
            $this->_cssParse(
                $file // source file
                , BASE_DIR . $_SERVER['REDIRECT_URL'] // cached file
            );
        } else {
            // source file not found
            http_response_code(404);
            die('HTTP Error 404: Page Not Found');
        }
    }


    function cssBase()
    {
        if ($file = BASE_DIR . dirname($this->route->getSelf()) . '/' . substr(basename($this->route->getSelf()), 0, -4) . '.less' and file_exists($file)) {
            // ok
            $this->_cssParse(
                 $file // source file
                 , BASE_DIR . $_SERVER['REDIRECT_URL'] // cached file
            );
        } else {
            // source file not found
            http_response_code(404);
            die('HTTP Error 404: Page Not Found');
        }
    }


    private function _cssParse($file, $file_cached = null)
    {
        // less css
        require_once LIBS_DIR . '/lessphp/lessc.inc.php';
        $less = new lessc($file);
        // parse
        $code = $less->parse();
        // cache
        if ($file_cached and DO_CACHING === true) {
            if (!is_dir($_dir = dirname($file_cached))) {
                mkdir($_dir, 0777, true);
            }
            file_put_contents($file_cached, $code);
        }
        // return
        header('Content-type: text/css; charset=utf-8');
        echo $code;
        exit;
    }
}
