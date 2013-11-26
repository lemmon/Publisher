<?php
/**
* 
*/
class Posts_Controller extends AbstractFrontend_Controller
{


    function category()
    {
        // nav
        if ($id = $this->route->id and $category = Category::find($id) and $page = false)
        {
            // current page
            $this->setCurrentPage($page, false);
            // template
            $this->data['category'] = $category;
            //
            return $this->template->display('posts_category');
        } else {
            // Category not found
            die('404');
        }
    }
}
