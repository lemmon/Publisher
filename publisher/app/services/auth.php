<?php
/**
 * Handles authentication
 * @see \Lemmon\Auth\AbstractAuth
 */
class Auth extends \Lemmon\Auth\AbstractAuth
{


	/**
	 * Init event.
	 */
	protected function __init()
	{
		$this->setIdentity($_SESSION['__AUTH__']);
	}


	/**
	 * Authenticate event.
	 * @param  string $username
	 * @param  string $password
	 * @see    \Lemmon\Auth\AbstractAuth::authenticate()
	 * @return mixed
	 */
	protected function onAuthenticate($username, $password)
	{
		if ($user = Users::find(['email' => $username])->first() and Auth::check($user->password, $password))
		{
			return $user->id;
		}
	}


	/**
	 * Store identity event.
	 * @param  mixed  $id identity
	 * @param  bool   $permanent
	 * @see    \Lemmon\Auth\AbstractAuth::authenticate()
	 */
	protected function onStoreIdentity($id, $permanent=false)
	{
		$_SESSION['__AUTH__'] = $id;
	}


	/**
	 * Return current identity event.
	 * @param  mixed  $id identity
	 * @see    \Lemmon\Auth\AbstractAuth::getIdentity()
	 * @return mixed
	 */
	protected function onGetIdentity($id)
	{
		return User::find($id);
	}


	/**
	 * Clear current identity event.
	 * @see    \Lemmon\Auth\AbstractAuth::clearIdentity()
	 */
	protected function onClearIdentity()
	{
		unset($_SESSION['__AUTH__']);
	}
}
