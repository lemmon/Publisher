<?php
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
			$locales = Lemmon\I18n\I18n::getLocales();
			foreach ($locales as $_code => $_caption)
			{
				$locales[$_code] = [
					'id'       => $_code,
					'caption'  => $_caption,
					'language' => [
						'name' => 'Foo',
					],
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


	static function fetchActive()
	{
		if (!array_key_exists('active', self::$_cache))
		{
			$active = array_flip(Db::getDefault()->query()->select('pages')->distinct('locale'));
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


	static function findAllWithPreferred()
	{
		if (!array_key_exists('preferred', self::$_cache))
		{
			$active = array_flip(Db::getDefault()->query()->select('pages')->distinct('locale'));
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
			return self::$_cache['preferred'] = [
				'active' => [
				'caption' => 'Active',
				'data'    => $res['active'],
				],
				'common' => [
					'caption' => 'Common',
					'data'    => $res['common'],
				],
				'others' => [
					'caption' => 'Others',
					'data'    => $res['others'],
				],
			];
		}
		else
		{
			return self::$_cache['preferred'];
		}
	}
}
