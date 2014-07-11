
DROP TABLE IF EXISTS `ht_response`;
DROP TABLE IF EXISTS `ht_message`;
DROP TABLE IF EXISTS `ht_notice`;
DROP TABLE IF EXISTS `ht_log`;
DROP TABLE IF EXISTS `ht_album`;
DROP TABLE IF EXISTS `ht_exam_result`;
DROP TABLE IF EXISTS `ht_exam_schedule`;
DROP TABLE IF EXISTS `ht_course_schedule`;
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
  `person` int(10) unsigned DEFAULT NULL,
  `code` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '0',
  `title` varchar(45) NOT NULL,
  `is_header` tinyint(1) unsigned DEFAULT '0',
  `level` varchar(45) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `Index_2` (`code`),
  KEY `FK_ht_teacher_person` (`person`),
  CONSTRAINT `FK_ht_teacher_person` FOREIGN KEY (`person`) REFERENCES `ht_person` (`person_id`)
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
  `person` int(10) unsigned NOT NULL,
  `code` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '0',
  `relation` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`guardian_id`),
  UNIQUE KEY `Index_2` (`code`),
  KEY `FK_ht_guardian_person` (`person`),
  CONSTRAINT `FK_ht_guardian_person` FOREIGN KEY (`person`) REFERENCES `ht_person` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- 表的结构 `ht_student`
--
CREATE TABLE  `ht_student` (
  `student_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person` int(10) unsigned NOT NULL,
  `code` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '0',
  `guardian` int(10) unsigned NOT NULL,
  `hteacher` int(10) unsigned NOT NULL,
  `class` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `Index_3` (`code`),
  KEY `FK_ht_student_class` (`class`),
  KEY `FK_ht_student_person` (`person`),
  KEY `FK_ht_student_hteacher` (`hteacher`),
  KEY `FK_ht_student_guardian` (`guardian`),
  CONSTRAINT `FK_ht_student_guardian` FOREIGN KEY (`guardian`) REFERENCES `ht_guardian` (`guardian_id`),
  CONSTRAINT `FK_ht_student_class` FOREIGN KEY (`class`) REFERENCES `ht_class` (`class_id`),
  CONSTRAINT `FK_ht_student_hteacher` FOREIGN KEY (`hteacher`) REFERENCES `ht_teacher` (`teacher_id`),
  CONSTRAINT `FK_ht_student_person` FOREIGN KEY (`person`) REFERENCES `ht_person` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `ht_course_schedule` (
  `cs_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `class` int(10) unsigned NOT NULL,
  `stage` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `subject` varchar(45) NOT NULL,
  `teacher` int(10) unsigned NOT NULL,
  `classroom` varchar(45) NOT NULL,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `validdate` date NOT NULL,
  `invaliddate` date NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`cs_id`),
  KEY `FK_ht_course_schedule_teacher` (`teacher`),
  CONSTRAINT `FK_ht_course_schedule_teacher` FOREIGN KEY (`teacher`) REFERENCES `ht_teacher` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `ht_exam_schedule` (
  `exam_id` int(10) unsigned NOT NULL,
  `code` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `class` int(10) unsigned NOT NULL,
  `subject` varchar(45) NOT NULL,
  `invigilator` varchar(45) NOT NULL,
  `validate` date NOT NULL,
  `invalidate` date NOT NULL,
  `is_active` tinyint(1) unsigned NOT NULL,
  `classroom` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE  `ht_exam_result` (
  `es_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `exam` int(10) unsigned NOT NULL,
  `student` int(10) unsigned NOT NULL,
  `score` float NOT NULL,
  `desc` varchar(45) DEFAULT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`es_id`),
  KEY `FK_ht_exam_result_1` (`student`),
  KEY `FK_ht_exam_result_2` (`exam`),
  CONSTRAINT `FK_ht_exam_result_2` FOREIGN KEY (`exam`) REFERENCES `ht_exam_schedule` (`exam_id`),
  CONSTRAINT `FK_ht_exam_result_1` FOREIGN KEY (`student`) REFERENCES `ht_student` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


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
