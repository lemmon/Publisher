<?php
/**
* 
*/
class QueryBanners extends AbstractQueryModel
{


    function __model()
    {
        return Banners::find(['state_id > ?' => 0]);
    }
}
