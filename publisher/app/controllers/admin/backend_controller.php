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
        if ($this->auth = new Auth and $this->auth->hasIdentity() and $user = $this->auth->getIdentity())
        {
            // user signed in
            $this->data['user'] = $this->user = $user;
        }
        elseif (self::getController() != 'admin/login')
        {
            // must login
            return $this->request->redir(':login');
        }
        //
        // i18n
        if ($i18n = $this->config['i18n'] and (is_string($i18n) or ($i18n = $i18n['admin'])))
        {
            if (file_exists($_file = USER_DIR . "/i18n/{$i18n}.php"))
            {
                Lemmon_I18n::setBase(dirname($_file));
                Lemmon_I18n::setLocale($i18n);
            }
        }
        //
        // templating
        $this->template = (new \Lemmon\Template\Template(ROOT_DIR . '/app/views', self::getAction()))
            ->appendFilesystem(self::getController())
            ->appendFilesystem(self::getController(), USER_DIR . '/app/views')
            ->setExtension(new TemplateExtensionAdmin);
            ;
    }


    protected function _res($res, $add = [])
    {
        if ($res instanceof \Lemmon\Route\Link or $f_errors = $this->flash->getErrors()) {
            header('Content-Type: application/json');
            echo json_encode([
                'redir' => (string)$res,
                'flash' => [
                    'errors' => $f_errors,
                    'fields' => $this->flash->getFields(),
                ],
            ] + (array)(is_callable($add) ? $add() : $add), JSON_PRETTY_PRINT);
            exit;
        }
    }
}
