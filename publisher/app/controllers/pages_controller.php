<?php
/**
* 
*/
class Pages_Controller extends Application
{

	function index()
	{
		#foreach (new Users as $user) dump($user);
		
		#$u = new Users;
		#dump($u->where(['id' => 3])->first());
		
		#dump(Users::find(2));
		#dump(Users::find()->first());
		#dump(Users::find()->all());
		#dump(Users::find(['is_admin' => 1])->first());
		#dump(Users::find(['is_admin' => null])->all());
		
		#Users::find(3)->getPosts();
		
		#$uv = UsersValues::find(['user_id' => 1]);
		
		#dump($uv);
		#dump($uv->all());
		
		#foreach (Users::find(1)->getValues()->order('value') as $value) dump($value);

		return false;
	}
}
