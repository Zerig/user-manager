# SSYTEM \ USER
- needs [**\SqlManager\Mysql**](https://github.com/Zerig/sql-manager) class
works with User account


## FIRST YOU NEED
```php
$GLOBALS["mysql"] = new \SqlManager\Mysql([
	"server_name"	=> "localhost",
	"db_user"	=> "root",
	"db_pass"	=> "",
	"db_name"	=> "test"
]);

$GLOBALS["mysql"]->multi_query("
	CREATE TABLE `user` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `user_type_id` smallint(2) DEFAULT NULL,
	  `login` varchar(150) COLLATE utf8_czech_ci NOT NULL,
	  `password` varchar(150) COLLATE utf8_czech_ci NOT NULL,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `login` (`login`),
	  KEY `user_type_id` (`user_type_id`),
	  CONSTRAINT `user_ibfk_2` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

	INSERT INTO `user` (`id`, `user_type_id`, `login`, `password`) VALUES
	(1,	1,	'zerig',	'$2y$15$"."u55Iz2kebEpW01bMLYYcReBGy5ZHoFvmRQyeaerGp0f8GnMLrbJEq');

	CREATE TABLE `user_type` (
	  `id` smallint(2) NOT NULL AUTO_INCREMENT,
	  `name` varchar(150) COLLATE utf8_czech_ci NOT NULL,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `name` (`name`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

	INSERT INTO `user_type` (`id`, `name`) VALUES
	(2,	'editor'),
	(1,	'admin');
");
```

```php
$GLOBALS["user"] = new \UserManager\User();
$GLOBALS["user"]->login("zerig", "123456");

public $id              => 1
public $user_type_id    => 1
public $user_type_name  => "admin"
public $login           => "zerig"

public static $pass_len => 6;	// MIN length of passwords

```
<br>
<hr>
<br>

## login($login, $password)
- **$login [string]** user name for login
- **$password [string]** user password for login
* **@return [boolean]** success of action

Log user into system.
```php
$GLOBALS["user"]->login("not_exist", "123456") => 0
$GLOBALS["user"]->isLogged() => 0

$GLOBALS["user"]->login("zerig", "123456")     => 1
$GLOBALS["user"]->isLogged() => 1
```

## logout()
* **@return [boolean]** success of action

Log user out of system.
```php
$GLOBALS["user"]->logout()   => 1
$GLOBALS["user"]->isLogged() => 0
```


## ::signup($login, $password, $type)
- **$login [string]** user name for login
- **$password [string]** user password for login
- **$type [num]** user_type_id
* **@return [boolean]** success of action

Add new user into MYSQL. New password length have to be min. 6 chars.
```php
\UserManager\User::signup("zerig", "555555", 1) => 0	// user already exist
\UserManager\User::signup("nym", "123", 1)      => 0	// password doesn't have 6 chars

\UserManager\User::signup("nym", "123456", 1)   => 1
$GLOBALS["user"]->login("nym", "123456")    => 1
$GLOBALS["user"]->isLogged() => 1
```

<hr>

## ::existUser($login)
- **$login [string]** user name
* **@return [boolean]** success of action

Add new user into MYSQL. New password length have to be min. 6 chars.
```php
\UserManager\User::existUser("not_exist") => 0
\UserManager\User::existUser("nym")       => 1
```

<hr>

## changePassword($old_password, $new_password, $control_password = null)
- **$old_password [string]** origin user password
- **$new_password [string]** new password
- **$constrol_password [string]** controling of the new password - not necessary
* **@return [boolean]** success of action

change password to new password
```php
$GLOBALS["user"]->changePassword("123456", "666")              => 0	// new password is too short
$GLOBALS["user"]->changePassword("123456", "666666")           => 1
$GLOBALS["user"]->changePassword("666666", "123", "123")       => 0	// new password and confirmation is too short
$GLOBALS["user"]->changePassword("666666", "123456", "123333") => 0	// new password and comfirmation is not the same
```

## changeLogin($new_login, $password)
- **$new_login [string]** new login name
- **$password [string]** origin user passworf for comfirmation
* **@return [boolean]** success of action

change password to new password
```php
$GLOBALS["user"]->changeLogin("nym2", "111111") => 0	// wrong password
$GLOBALS["user"]->changeLogin("nym2", "666666") => 1	// right password
```


<hr>

## ::removeUser($login)
- **$login [string]** user name login to be deleted
* **@return [boolean]** success of action

Delete user from Mysql.
```php
\UserManager\User::removeUser("not_exist") => 1	// not existing user
\UserManager\User::removeUser("nym")       => 1	// existing user
```
