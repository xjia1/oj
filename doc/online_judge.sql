SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `permissions` (
  `user_name` varchar(100) NOT NULL,
  `permission_name` varchar(100) NOT NULL,
  KEY `user_name` (`user_name`,`permission_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `problems` (
  `id` bigint(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `author` varchar(100) NOT NULL,
  `case_count` int(11) NOT NULL,
  `case_score` int(11) NOT NULL,
  `time_limit` int(11) NOT NULL,
  `memory_limit` int(11) NOT NULL,
  `secret_before` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `author` (`author`),
  KEY `secret_before` (`secret_before`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `profiles` (
  `username` varchar(30) NOT NULL DEFAULT '',
  `realname` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `records` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `owner` varchar(100) NOT NULL,
  `problem_id` bigint(20) NOT NULL,
  `submit_code` longtext NOT NULL,
  `code_language` tinyint(4) NOT NULL,
  `submit_datetime` datetime NOT NULL,
  `judge_status` tinyint(4) NOT NULL,
  `judge_message` text NOT NULL,
  `verdict` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `problem_id` (`problem_id`),
  KEY `code_language` (`code_language`),
  KEY `submit_datetime` (`submit_datetime`),
  KEY `judge_status` (`judge_status`),
  KEY `verdict` (`verdict`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `visible` tinyint(4) NOT NULL,
  `title` varchar(100) NOT NULL,
  `problem_list` text NOT NULL,
  `user_list` text NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `visible` (`visible`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(45) DEFAULT NULL,
  `use_git` int(11) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `variables` (
  `name` varchar(100) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_stats` (
  `username` varchar(30) NOT NULL,
  `solved` bigint NOT NULL,
  `tried` bigint NOT NULL,
  `submissions` bigint NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
