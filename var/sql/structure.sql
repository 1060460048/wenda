--
-- 表的结构 `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `finale` enum('N','Y') NOT NULL DEFAULT 'N',
  `ip_address` int(11) NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL DEFAULT '0',
  `status` enum('A','D') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  KEY `question_id_idx` (`question_id`),
  KEY `parent_id_idx` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `scores` int(10) unsigned NOT NULL DEFAULT '0',
  `isnew` enum('N','Y') NOT NULL DEFAULT 'N',
  `sort_order` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id_idx` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `province` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `province_idx` (`province`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('system','member') NOT NULL DEFAULT 'member',
  `title` varchar(50) NOT NULL DEFAULT '',
  `grade` int(11) NOT NULL DEFAULT '0',
  `credits_lower` int(11) DEFAULT '0',
  `credits_higher` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type_idx` (`type`),
  KEY `credits_idx` (`credits_lower`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `keyword`
--

CREATE TABLE IF NOT EXISTS `keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `value` varchar(60) NOT NULL DEFAULT '',
  `word` varchar(50) NOT NULL DEFAULT '',
  `scores` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cate_value_idx` (`category_id`,`value`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name_idx` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(10) NOT NULL DEFAULT '',
  `category_id` INT(10) UNSIGNED NOT NULL,
  `reward` SMALLINT(6) NULL DEFAULT '0',
  `pageviews` INT(10) UNSIGNED NULL DEFAULT '0',
  `answers` INT(10) UNSIGNED NULL DEFAULT '0',
  `votes` TINYINT(4) NOT NULL DEFAULT '0',
  `collects` TINYINT(4) UNSIGNED NOT NULL DEFAULT '0',
  `ip_address` INT(11) NOT NULL DEFAULT '0',
  `finish` ENUM('Y','N') NOT NULL DEFAULT 'N',
  `last_answer_at` INT(10),
  `resolved_at` INT(10) NOT NULL DEFAULT '0',
  `expired_at` INT(10) NOT NULL DEFAULT '0',
  `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` INT(10) NOT NULL DEFAULT '0',
  `updated_at` INT(10) NOT NULL DEFAULT '0',
  `status` ENUM('A','D') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`),
  INDEX `token_id_idx` (`token`, `id`),
  INDEX `created_idx` (`created_at`),
  INDEX `user_id_idx` (`user_id`),
  INDEX `category_idx` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `question_content`
--

CREATE TABLE IF NOT EXISTS `question_content` (
  `question_id` int(10) unsigned NOT NULL,
  `subject` varchar(100) NOT NULL DEFAULT '',
  `addition` varchar(250) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `keywords` varchar(250) NOT NULL DEFAULT '',
  `created_at` int(11) NOT NULL DEFAULT '0',
  KEY `question_id` (`question_id`),
  FULLTEXT KEY `keywords` (`keywords`),
  FULLTEXT KEY `subject` (`subject`,`content`,`keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(64) NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT '',
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `activation_key` varchar(64) NOT NULL DEFAULT '',
  `gender` enum('girl','boy') NOT NULL DEFAULT 'boy',
  `city` varchar(20) NOT NULL DEFAULT '',
  `birthday` int(11) NOT NULL DEFAULT '0',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `msn` varchar(50) NOT NULL DEFAULT '',
  `introduce` text NOT NULL,
  `logo` varchar(250) NOT NULL DEFAULT '',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL DEFAULT '0',
  `status` enum('A','D') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_idx` (`name`),
  UNIQUE KEY `email_idx` (`email`),
  KEY `group_id_idx` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
