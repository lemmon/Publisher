<?php
/**
* 
*/
class Admin_Pages_Controller extends Application
{
	
	function index()
	{
		if (count($languages=Languages::getActive()))
		{
			$this->data['languages'] = $languages;
		}
		else
		{
			return \Lemmon\Template::display('first', $this->getData(1));
		}
	}


	function create()
	{


		$this->data['languages_collection'] = Languages::getCollection();
		$this->data['pages_states_collection'] = $ps = PagesStates::getCollection();
		
		foreach ($ps as $i => $j)
		{
			dump($j);
		}
		
		dump($ps);
		die;
	}


	function update()
	{
	}
}
