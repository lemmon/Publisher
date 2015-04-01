<?php
/**
* 
*/
class Admin_Seo_Controller extends Admin_Backend_Controller
{


    function index()
    {
        #header('Content-Type: text/plain; charset=utf-8');
        echo '<pre>';
        foreach (Pages::fetchAllByLanguage()['langs'] as $lang => $pages) {
            echo '-- ', $lang, ' --', "\n\n";
            foreach ($pages as $page) {
                echo $page->name, "\n";
                echo '  ', $page->getUrl(), "\n";
                echo sprintf('  tt (%2$3d) %1$s', $_ = (($_1 = $page->getBlock('title')) ? $_1 : sprintf('%s - %s', $this->site->name, $page->name)), mb_strlen($_)), "\n";
                echo sprintf('  md (%2$3d) %1$s', $_ = $page->getBlock('meta-description'), strlen($_)), "\n";
                echo "\n";
            }
            echo "\n";
        }
        echo '</pre>';
        exit;
    }
}
