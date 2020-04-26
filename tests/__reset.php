<?php
$GLOBALS["mysql"] = new \SqlManager\Mysql([
	"server_name"	=> "sql.server.cz",
	"db_user"	=> "server_user",
	"db_pass"	=> "123456",
	"db_name"	=> "my_server_db"
],[
	"server_name"	=> "localhost",
	"db_user"	=> "root",
	"db_pass"	=> "",
	"db_name"	=> "test"
]);

$GLOBALS["mysql"]->multi_query("
-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `user`;
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

DROP TABLE IF EXISTS `user_type`;
CREATE TABLE `user_type` (
  `id` smallint(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `user_type` (`id`, `name`) VALUES
(2,	'editor'),
(1,	'správce');

-- 2020-04-25 21:39:54
");
