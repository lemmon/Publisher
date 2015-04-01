<?php
/**
* 
*/
abstract class Admin_Backend_Controller extends Application
{
    protected $auth;
    protected $user;


    final protected function __initSection()
    {
        //
        // auth
        if ($this->auth = new Auth and $this->auth->hasIdentity() and $user = $this->auth->getIdentity()) {
            // user signed in
            $this->data['user'] = $this->user = $user;
        }
        elseif (self::getController() != 'admin/login') {
            // must login
            return $this->route->to(':admin/login');
        }
        //
        // i18n
        $this->i18n->setLocale($this->site->locale_id);
        $this->i18n->load(BASE_DIR . "/i18n/{$this->site->locale_id}/admin.php");
        $this->i18n->load(USER_DIR . "/i18n/{$this->site->locale_id}/admin.php");
        //
        // templating
        $this->template = (new \Lemmon\Template\Template(ROOT_DIR . '/app/views', self::getAction()))
            ->appendFilesystem('_module', ROOT_DIR . '/app/views/admin')
            ->appendFilesystem(self::getController(), ROOT_DIR . '/app/views')
            ->appendFilesystem(self::getController(), USER_DIR . '/app/views')
            ->setExtension(new TemplateExtensionAdmin($this->i18n));
            ;
        //
        // frontend cache control
        if ($_POST and $user and DO_CACHING === TRUE) {
            $this->cache->flush();
        }
    }


    protected function getPage($id = null)
    {
        if (($id or $id = $this->route->id) and $page = Page::find($id)) {
            // page found
            return $this->data['page'] = $this->page = $page;
        } else {
            // n/a
            return $this->route->notFound();
        }
    }


    protected function _res($res = null, $add = [])
    {
        // Route instance
        if ($res instanceof \Lemmon\Route\Link or $f_errors = $this->flash->getErrors() /*or $f_notices = $this->flash->getNotices()*/) {
            header('Content-Type: application/json');
            echo json_encode([
                'redir' => (string)$res,
                'flash' => [
                    'errors' => $f_errors,
                    'fields' => $this->flash->getFields(),
                    'notices' => $f_notices,
                ],
            ] + (array)(is_callable($add) ? $add() : $add), JSON_PRETTY_PRINT);
            exit;
        }
        // function
        elseif (is_callable($res)) {
            return $this->_res($res(), $add);
        }
    }
}
