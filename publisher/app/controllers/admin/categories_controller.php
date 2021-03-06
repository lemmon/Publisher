<?php

use \Lemmon\Form\Scaffold,
    \Lemmon\Sql\Query as SqlQuery;

/**
* 
*/
class Admin_Categories_Controller extends Admin_Backend_Controller
{


    function main()
    {
        if ($data = Categories::find()->count())
        {
            $this->data += [
                'locales'    => Locales::fetchActive(),
                'categories' => Categories::fetchActiveByLocale(),
            ];
        }
        else
        {
            return $this->template->display('empty');
        }
    }


    function index()
    {
        
    }


    private function _getOptions()
    {
        $this->data += [
            /*
            'locales' => Locales::fetchActive(),
            */
            'states'  => States::getOptions(),
        ];
    }


    function create()
    {
        // options
        $this->_getOptions();
        // scaffolding
        return Scaffold::create($this, [
            'default' => $_SESSION['defaults'],
        ]);
    }


    function update()
    {
        // options
        $this->_getOptions();
        // scaffolding
        return Scaffold::update($this);
    }


    function delete()
    {
        if ($id = $this->route->id and $item = Category::find($id))
        {
            (new SqlQuery)->delete('posts_to_categories')->where('category_id', $id)->exec();
            (new SqlQuery)->delete('categories')->where('id', $id)->exec();
            $this->flash->setNotice('Category deleted');
            return $this->route->to(':admin/section');
        }
        else
        {
            $this->flash->setError('Error');
            return $this->route->to(':admin/section');
        }
    }
}
