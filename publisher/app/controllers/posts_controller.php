<?php
/**
* 
*/
class Posts_Controller extends AbstractFrontend_Controller
{


    function index()
    {
        $this->data += [
            'posts' => (new QueryPosts(['page_id' => $this->page->id])),
        ];
    }


    function detail()
    {
        // nav
        if ($id = $this->route->id and $post = Post::find($id) and $page = Page::find($post->page_id)) {
            // current page
            $this->setCurrentPage($page, false);
            // template
            $this->data['post'] = $post;
            //
            return $this->template->display('posts_detail');
        } else {
            // Post not found
            die('404');
        }
    }


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
