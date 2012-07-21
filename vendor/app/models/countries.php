<?php
/**
* 
*/
class Countries extends Lemmon_Model
{

	protected function define()
	{
		$this->sort('name');
	}
	
	static public function getByVisitor()
	{
		if (array_key_exists( '__COUNTRY__', $_SESSION ))
		{
			return $_SESSION[ '__COUNTRY__' ];
		}
		elseif ($host=gethostbyaddr(Application::getIP()) and substr($host, -3, 1)=='.' and $country=substr($host, -2) and $country=Countries::make()->findLike('name_short', $country)->first()->name_short)
		{
			return $_SESSION[ '__COUNTRY__' ]=$country;
		}
		elseif ($country=self::fetchByIp(Application::getIp()) and $country!='XX')
		{
			return $_SESSION[ '__COUNTRY__' ]=$country;
		}
		else
		{
			return $_SESSION[ '__COUNTRY__' ]=null;
		}
	}

	static public function fetchByIp($ip)
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, 'http://api.hostip.info/country.php?ip=' . $ip);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'); 
		$country = curl_exec($ch);
		curl_close($ch);
		return $country;
	}
}
