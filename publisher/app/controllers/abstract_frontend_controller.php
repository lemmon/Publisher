<?php
/**
* 
*/
abstract class AbstractFrontend_Controller extends Application
{


    final protected function __initSection()
    {
        //
        // app environment
        Application::$isFrontend = true;
        //
        // i18n
        if ($i18n = $this->config['i18n'] and (is_string($i18n) or ($i18n = $i18n['front']))) {
            if (file_exists($_file = USER_DIR . "/i18n/{$i18n}.php")) {
                Lemmon_I18n::setBase(dirname($_file));
                Lemmon_I18n::setLocale($i18n);
            }
        }
        //
        // templates
        $this->template = (new \Lemmon\Template\Template(USER_DIR . '/template', 'index'))
            ->setExtension(new TemplateExtensionUser);
        //
        // default services
        $this->data += [
            'nav'   => new Nav,
            'query' => new Query,
        ];
        //
        // page
        if ($page = $this->page) {
            $this->data['page'] = $this->page;
            Nav::setCurrentPage($page, (string)$page->getUrl() == (string)$this->route->getSelf());
            $this->template->display($page->template ?: ($page->type ? ($page->type . (self::getAction() == 'index' ? '' : '_' . self::getAction())) : 'default'));
        }
        //
        // init
        $this->__initModule();
    }


    protected function __initModule() {}


    protected function _res($res = null, $add = [], $default = null)
    {
        // array
        if (is_array($res)) {
            header('Content-Type: application/json');
            echo json_encode($res + (array)(is_callable($add) ? $add() : $add), JSON_PRETTY_PRINT);
            exit;
        }
        // Route instance
        if ($res instanceof \Lemmon\Route\Link) {
            return $this->_res([
                'redir' => (string)$res,
            ], $add);
        }
        // function
        elseif (is_callable($res)) {
            return $this->_res($res(), $add, $default);
        }
        // on error
        elseif ($f_errors = $this->flash->getErrors()) {
            return $this->_res([
                'flash' => [
                    'errors' => $f_errors,
                    'fields' => $this->flash->getFields(),
                ],
            ], $add);
            exit;
        }
        // on error
        elseif ($f_notices = $this->flash->getNotices()) {
            return $this->_res([
                'flash' => [
                    'notices' => $f_notices,
                ],
            ], $add);
            exit;
        }
        // default
        else {
            return is_callable($default) ? $default() : $default;
        }
    }
}
