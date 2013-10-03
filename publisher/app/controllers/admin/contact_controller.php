<?php
/**
* 
*/
class Admin_Contact_Controller extends Admin_Forms_Controller
{


    function details()
    {
        // page
        $page = $this->getPage();
        // form
        return $this->_res(function() use ($page){
        
            $scope = 'contact';
            // on POST
            if ($f = $_POST){
                /*
                if ($f['scope'] == 'locale') {
                    $scope = 'contact:' . $page->locale_id;
                } elseif ($f['scope'] == 'section') {
                    $scope = 'contact:' . $page->id;
                } else {
                    $scope = 'contact';
                }
                */
                $in = [
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
                ];
                Values::putMany($scope, $in);
                $this->flash->setNotice('Contact info has been updated');
                return $this->route->getSection($page);
            }
            // load values
            else {
                /*
                if ($page->getBlock('contact') == 'locale') {
                    $scope = "contact:{$page->locale_id}";
                    $this->data['f']['scope'] = 'locale';
                } elseif ($page->getBlock('contact') == 'section') {
                    $scope = "contact:{$page->id}";
                    $this->data['f']['scope'] = 'section';
                } else {
                    $scope = 'contact';
                }
                */
                $this->data['f'] += (array)Values::getMany($scope);
            }
            // form
            /*
            $others = Values::get('contact/%');
            unset($others[$scope]);
            if ($others) {
                $this->data['parents'] = array_intersect_key(Locales::fetchAll(), array_flip(preg_filter('#contact/([a-z]+_[A-Z]+)#', '$1', array_keys($others))));
            }
            */
        
        });
    }
}
