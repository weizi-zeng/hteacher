
DROP TABLE IF EXISTS `ht_response`;
DROP TABLE IF EXISTS `ht_message`;
DROP TABLE IF EXISTS `ht_notice`;
DROP TABLE IF EXISTS `ht_log`;
DROP TABLE IF EXISTS `ht_album`;
DROP TABLE IF EXISTS `ht_score`;
DROP TABLE IF EXISTS `ht_exam`;
DROP TABLE IF EXISTS `ht_course`;
DROP TABLE IF EXISTS `ht_student`;
DROP TABLE IF EXISTS `ht_guardian`;
DROP TABLE IF EXISTS `ht_class`;
DROP TABLE IF EXISTS `ht_grade`;
DROP TABLE IF EXISTS `ht_teacher`;
DROP TABLE IF EXISTS `ht_person`;


-- --------------------------------------------------------
--
-- 表的结构 `ht_person`
--
CREATE TABLE `ht_person` (
  `person_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '1',
  `class_code` varchar(20) NOT NULL,
  `iden` varchar(45) DEFAULT NULL COMMENT '身份',
  `id_card` varchar(45) DEFAULT NULL COMMENT '身份证',
  `sex` tinyint(1) unsigned DEFAULT NULL,
  `bthday` date DEFAULT NULL,
  `nation` varchar(45) DEFAULT NULL,
  `tel` varchar(45) DEFAULT NULL,
  `shorttel` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `unit` varchar(45) DEFAULT NULL,
  `has_left` tinyint(1) unsigned DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- 表的结构 `ht_teacher`
--
CREATE TABLE  `ht_teacher` (
  `teacher_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `sex` smallint(1) unsigned DEFAULT '1',
  `birthday` date DEFAULT NULL,
  `national` varchar(45) DEFAULT NULL,
  `id_card` varchar(45) DEFAULT NULL,
  `phone` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `title` varchar(45) NOT NULL,
  `is_header` smallint(1) unsigned DEFAULT '0',
  `level` varchar(45) DEFAULT NULL,
  `class_code` varchar(45) NOT NULL,
  `is_active` smallint(1) unsigned DEFAULT '1',
  `has_left` smallint(1) unsigned DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- 表的结构 `ht_grade`
--
CREATE TABLE  `ht_grade` (
  `grade_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `others` varchar(45) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`grade_id`),
  UNIQUE KEY `Index_2` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- 表的结构 `ht_class`
--
CREATE TABLE  `ht_class` (
  `class_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '0',
  `classroom` varchar(45) NOT NULL,
  `hteacher` int(10) unsigned NOT NULL,
  `has_left` tinyint(1) unsigned DEFAULT '0',
  `removed` tinyint(1) unsigned DEFAULT '0',
  `grade` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`class_id`) USING BTREE,
  UNIQUE KEY `Index_2` (`code`),
  KEY `FK_ht_class_teacher` (`hteacher`),
  KEY `FK_ht_class_grade` (`grade`),
  CONSTRAINT `FK_ht_class_teacher` FOREIGN KEY (`hteacher`) REFERENCES `ht_teacher` (`teacher_id`),
  CONSTRAINT `FK_ht_class_grade` FOREIGN KEY (`grade`) REFERENCES `ht_grade` (`grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- 表的结构 `ht_guardian`
--
CREATE TABLE  `ht_guardian` (
  `guardian_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `sex` smallint(1) unsigned DEFAULT '1',
  `birthday` date DEFAULT NULL,
  `national` varchar(45) DEFAULT NULL,
  `id_card` varchar(45) DEFAULT NULL,
  `phone` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `uint` varchar(45) DEFAULT NULL,
  `student_id` int(10) unsigned DEFAULT NULL,
  `student_name` varchar(45) DEFAULT NULL,
  `relationship` varchar(45) DEFAULT NULL,
  `class_code` varchar(45) NOT NULL,
  `has_left` smallint(1) unsigned DEFAULT '0',
  `is_actiove` smallint(1) unsigned DEFAULT '1',
  `created` datetime NOT NULL,
  PRIMARY KEY (`guardian_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- 表的结构 `ht_student`
--
CREATE TABLE  `ht_student` (
  `student_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `sex` smallint(1) unsigned DEFAULT '1',
  `birthday` date DEFAULT NULL,
  `national` varchar(20) DEFAULT NULL,
  `id_card` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `class_code` varchar(45) NOT NULL,
  `guardian_id` int(10) unsigned DEFAULT NULL,
  `guardian_name` varchar(45) DEFAULT NULL,
  `guardian_relation` varchar(45) DEFAULT NULL,
  `guardian_phone` varchar(45) DEFAULT NULL,
  `license` varchar(45) DEFAULT '',
  `has_left` smallint(1) unsigned DEFAULT '0',
  `is_active` smallint(1) unsigned DEFAULT '1',
  `created` datetime NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE  `ht_course` (
  `course_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `semster` varchar(45) NOT NULL,
  `weekday` smallint(2) NOT NULL,
  `class_code` varchar(45) NOT NULL,
  `stage` varchar(45) NOT NULL DEFAULT '1',
  `subject` varchar(45) NOT NULL,
  `teacher` varchar(45) NOT NULL,
  `stime` time NOT NULL,
  `etime` time NOT NULL,
  `classroom` varchar(45) NOT NULL,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`course_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `ht_exam` (
  `exam_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `class_code` varchar(45) NOT NULL,
  `subject` varchar(45) NOT NULL,
  `teacher` varchar(45) NOT NULL,
  `examdate` date NOT NULL,
  `stime` time NOT NULL,
  `etime` time NOT NULL,
  `classroom` varchar(45) NOT NULL,
  `closed` smallint(1) DEFAULT '0',
  `created` varchar(45) NOT NULL,
  PRIMARY KEY (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `ht_score` (
  `score_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_code` varchar(20) NOT NULL,
  `exam_id` int(10) unsigned NOT NULL,
  `student_code` varchar(20) NOT NULL,
  `score` float NOT NULL,
  `add_score` float NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`score_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


CREATE TABLE  `ht_album` (
  `album_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` int(10) unsigned NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `path` varchar(45) NOT NULL,
  `filesize` varchar(45) DEFAULT NULL,
  `removed` tinyint(1) unsigned DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `ht_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(45) NOT NULL,
  `content` varchar(45) NOT NULL,
  `actor` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `ht_notice` (
  `notice_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(45) DEFAULT NULL,
  `class` int(10) unsigned NOT NULL,
  `notice` varchar(45) NOT NULL,
  `auther` varchar(45) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`notice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `ht_message` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  `msg` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `tel` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `qq` varchar(45) DEFAULT NULL,
  `has_response` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `person` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE  `ht_response` (
  `response_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  `msg` int(10) unsigned NOT NULL,
  `response` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `person` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`response_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `ht_admin_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `log_info` varchar(255) NOT NULL DEFAULT '',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`),
  KEY `log_time` (`log_time`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE  `ht_admin_message` (
  `message_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `receiver_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sent_time` int(11) unsigned NOT NULL DEFAULT '0',
  `read_time` int(11) unsigned NOT NULL DEFAULT '0',
  `readed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `sender_id` (`sender_id`,`receiver_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ht_sms`;
CREATE TABLE  `ht_sms` (
  `sms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(512) NOT NULL,
  `phones` varchar(1024) NOT NULL,
  `status` smallint(2) unsigned NOT NULL DEFAULT '0',
  `num` int(11) DEFAULT '0',
  `class_code` varchar(20) NOT NULL,
  `sended` datetime DEFAULT NULL,
  `creator` varchar(40) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`sms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;