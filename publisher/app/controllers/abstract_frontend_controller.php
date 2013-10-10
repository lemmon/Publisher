<?php
/**
* 
*/
abstract class AbstractFrontend_Controller extends Application
{


    final protected function __initSection()
    {
        //
        // templates
        $this->template = (new TemplateFrontend(ROOT_DIR . '/app/template', 'index'))
            ->appendFilesystem(ROOT_DIR . '/app/views/mixins')
            ->appendFilesystem(USER_DIR . '/template')
            ->setExtension(new TemplateExtensionUser($this->i18n))
            ->setCache($this->cache);
        //
        // default services
        $this->data += [
            'nav'   => new Nav($this->site),
            'query' => new Query,
        ];
        //
        // page
        if ($page = $this->page) {
            $this->data['page'] = $this->page;
            $this->setCurrentPage($page, (string)$page->getUrl() == (string)$this->route->getSelf());
            $this->template->display($page->getTemplateName());
        }
        //
        // init
        $this->__initModule();
    }


    protected function setCurrentPage($page, $active = true)
    {
        // i18n
        $this->i18n->setLocale($page->locale_id);
        $this->i18n->load(USER_DIR . "/i18n/{$page->locale_id}/frontend.php");
        // nav
        Nav::setCurrentPage($page, $active);
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
