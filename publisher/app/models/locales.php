<?php

use \Lemmon\I18n\I18n;

/**
* 
*/
class Locales
{
	protected static $common = ['en_US', 'en_GB', 'sk_SK', 'cs_CZ', 'pl_PL', 'hu_HU', 'ru_RU', 'es_ES', 'de_DE', 'fr_FR'];
	
	private static $_cache = [];


	static function fetch($locale)
	{
		return self::fetchAll()[$locale];
	}


	static function fetchAll()
	{
		if (!array_key_exists('locales', self::$_cache))
		{
			$locales = I18n::getLocales();
			foreach ($locales as $_code => $_caption)
			{
				$locales[$_code] = [
					'id'       => $_code,
					'caption'  => $_caption,
					'country'  => [
						'code' => explode('_', $_code)[1],
					],
				];
			}
			return self::$_cache['locales'] = $locales;
		}
		else
		{
			return self::$_cache['locales'];
		}
	}


	private static function _getActive()
	{
		return array_flip(array_merge(
			Db::getDefault()->query()->select('pages')->distinct('locale'),
			Db::getDefault()->query()->select('posts')->distinct('locale'),
			Db::getDefault()->query()->select('categories')->distinct('locale')
		));
	}


	static function fetchActive()
	{
		if (!array_key_exists('active', self::$_cache))
		{
			$active = self::_getActive();
			$locales = self::fetchAll();
			$res = [];
			foreach ($locales as $_code => $_locale)
			{
				if ($active[$_code] !== null)
					$res[$_code] = $_locale;
			}
			return self::$_cache['active'] = $res;
		}
		else
		{
			return self::$_cache['active'];
		}
	}


	static function fetchAllWithPreferred()
	{
		if (!array_key_exists('preferred', self::$_cache))
		{
			$active = self::_getActive();
			$common = array_flip(self::$common);
			$locales = self::fetchAll();
			$res = [];
			foreach ($locales as $_code => $_locale)
			{
				if ($active[$_code] !== null)
					$res['active'][$_code] = $_locale;
				elseif ($common[$_code] !== null)
					$res['common'][$_code] = $_locale;
				else
					$res['others'][$_code] = $_locale;
			}
			$preferred = [];
			if ($res['active'])
			{
				$preferred['active'] = [
					'caption' => 'Active',
					'data'    => $res['active'],
				];
			}
			$preferred['common'] = [
				'caption' => 'Common',
				'data'    => $res['common'],
			];
			/*
			$preferred['others'] = [
				'caption' => 'Others',
				'data'    => $res['others'],
			];
			*/
			return self::$_cache['preferred'] = $preferred;
		}
		else
		{
			return self::$_cache['preferred'];
		}
	}
}
