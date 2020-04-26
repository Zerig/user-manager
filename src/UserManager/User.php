<?php
namespace UserManager;


class User{
	public $id;
	public $user_type_id;
	public $user_type_name;
	public $login;


	public static $pass_len = 6;



	public function __construct(){

	}



	public function login($login, $password){
		$_user = $GLOBALS["mysql"]->query_("
			SELECT user.*, user_type.name as user_type_name
			FROM user
			LEFT JOIN user_type

			ON user.user_type_id = user_type.id
			WHERE login = '".$login."'
			LIMIT 1
		");



		if(is_object($_user) && password_verify($password, $_user->password)){
			foreach($_user as $key => $value){
				if(!is_numeric($key)){
					$this->{$key} = $value;

					$this->key[] = $key;
					$this->val[] = $value;
				}
			}
			return true;
		}else{
			return false;
		}
	}

	public function logout(){
		foreach(get_object_vars($this) as $key => $val){
			unset($this->{$key});
		}
	}

	public function isLogged(){
		if(isset($this->login))	return true;
		else   					return false;
	}









	public static function signup($login, $password, $type){
		if(strlen($password) >= self::$pass_len){
			return $GLOBALS["mysql"]->insert("user", [
				"login" 		=> $login,
				"password"		=> self::encodePassword($password),
				"user_type_id"	=> $type
			]);
		}
		return false;
	}


	private static function encodePassword($password){
		$hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 15]);
		return $hash;
	}




	public function changePassword($old_password, $new_password, $control_password = null){
		$user_password = self::getPassword();
		if(is_null($user_password)) 											return false;
		if(!is_null($control_password) && $new_password != $control_password) 	return false;
		if(strlen($new_password) < self::$pass_len)								return false;

		if(password_verify($old_password, $user_password)){
			return $GLOBALS["mysql"]->update("user", [
				"password" => self::encodePassword($new_password)
			], "id='".$this->id."'");
		}

		return false;
	}



	public function changeLogin($new_login, $password){
		$user_password = self::getPassword();
		if(is_null($user_password)) 					return false;
		if(!password_verify($password, $user_password))	return false;

		return $GLOBALS["mysql"]->update("user", [
			"login" => $new_login
		], "id='".$this->id."'");
	}



	private function getPassword(){
		$_user = $GLOBALS["mysql"]->query_("
			SELECT *
			FROM user
			WHERE login = '".$this->login."'
			LIMIT 1
		");
		if(is_object($_user)) return $_user->password;
		return null;
	}



	public static function existUser($login){
		$_user = $GLOBALS["mysql"]->query_("
			SELECT *
			FROM user
			WHERE login = '".$login."'
			LIMIT 1
		");

		return (is_object($_user))? true : false;
	}


	public static function removeUser($login){
		return $GLOBALS["mysql"]->delete("user", "login = '".$login."'");
	}








}
