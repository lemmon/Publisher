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
            $action = self::getAction() != 'index' ? self::getAction() : null;
            $this->template->display(file_exists(USER_DIR . '/template/' . $page->getTemplateName($action) . '.html') ? $page->getTemplateName($action) : 'default');
        }
        //
        // init
        $this->__initModule();
    }


    protected function __initModule() {}


    protected function setCurrentPage($page, $active = true)
    {
        // i18n
        $this->i18n->setLocale($page->locale_id);
        $this->i18n->load(BASE_DIR . "/i18n/{$page->locale_id}/template.php");
        $this->i18n->load(USER_DIR . "/i18n/{$page->locale_id}/template.php");
        // nav
        Nav::setCurrentPage($page, $active);
    }


    function index()
    {
        $class_name = substr(get_class($this), 0, -11);
        $query_class_name = 'Query' . $class_name;
        $this->data[\Lemmon\String::classToTableName($class_name)] = new $query_class_name($this->page);
    }


    function detail()
    {
        $class_name = \Lemmon\String::sg(substr(get_class($this), 0, -11));
        // nav
        if ($id = $this->route->id and $item = call_user_func([$class_name, 'find'], $id) and $page = Page::find($item->page_id)) {
            // current page
            $this->setCurrentPage($page, false);
            // template
            $this->data[\Lemmon\String::classToTableName($class_name)] = $item;
        } else {
            // Post not found
            die('404');
        }
    }


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
