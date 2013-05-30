<?php
/**
* 
*/
class States
{


    static function getOptions()
    {
        return [
               1 => ['id' =>    1, 'name' => 'Active', 'caption' => 'Active'],
              -1 => ['id' =>   -1, 'name' => 'Hidden', 'caption' => 'Hidden'],
            null => ['id' => null, 'name' => 'Draft',  'caption' => 'Draft' ],
        ];
    }
}

