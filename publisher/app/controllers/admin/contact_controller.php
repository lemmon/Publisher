<?php
/**
* 
*/
class Admin_Contact_Controller extends Admin_Forms_Controller
{


    function details()
    {
        if ($page = $this->getPage($this->route->id)) {
            // update contact details
            return $this->_res(function() use ($page){
            
                // on POST
                if ($f = $_POST){
                    if ($f['scope'] == 'locale') {
                        $key = 'contact/' . $page->locale_id;
                    } elseif ($f['scope'] == 'section') {
                        $key = 'contact/' . $page->id;
                    } else {
                        $key = 'contact';
                    }
                    Values::put($key, $this->sanitize([
                        'name'      => $f['name'],
                        'address'   => $f['address'],
                        'zip'       => $f['zip'],
                        'city'      => $f['city'],
                        'country'   => $f['country'],
                        'phone'     => $f['phone'],
                        'fax'       => $f['fax'],
                        'email'     => $f['email'],
                        'geo_lat'   => $f['geo_lat'],
                        'geo_lng'   => $f['geo_lng'],
                    ], true));
                    $page->setBlock('contact', $f['scope'])->save();
                    $this->flash->setNotice('Contact info has been updated');
                    return $this->route->getSection($page);
                }
                // load values
                else {
                    if ($page->getBlock('contact') == 'locale') {
                        $key = 'contact/' . $page->locale_id;
                        $this->data['f']['scope'] = 'locale';
                    } elseif ($page->getBlock('contact') == 'section') {
                        $key = 'contact/' . $page->id;
                        $this->data['f']['scope'] = 'section';
                    } else {
                        $key = 'contact';
                    }
                    $this->data['f'] += Values::get($key);
                }
                // form
                $others = Values::get('contact/%');
                unset($others[$key]);
                if ($others) {
                    $this->data['parents'] = array_intersect_key(Locales::fetchAll(), array_flip(preg_filter('#contact/([a-z]+_[A-Z]+)#', '$1', array_keys($others))));
                }
            
            });
        } else {
            // Page not found
            die('Error.');
        }
    }


    protected function sanitize(array $f, $remove_empty = false)
    {
        foreach ($f as $key => $val) {
            if ($val) {
                $f[$key] = trim($val);
            } elseif ($remove_empty) {
                unset($f[$key]);
            }
        }
        return $f;
    }
}
