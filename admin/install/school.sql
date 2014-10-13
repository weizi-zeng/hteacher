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
DROP TABLE IF EXISTS `ht_notice_attach`;


-- --------------------------------------------------------
--
-- 表的结构 `ht_teacher`
--
CREATE TABLE  `ht_teacher` (
  `teacher_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `sexuality` smallint(1) unsigned DEFAULT '1',
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
  `class_id` int(10) unsigned NOT NULL auto_increment,
  `code` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `is_active` tinyint(1) unsigned default '1',
  `classroom` varchar(45) NOT NULL,
  `hteacher` varchar(45) NOT NULL,
  `has_left` tinyint(1) unsigned default '0',
  `removed` tinyint(1) unsigned default '0',
  `grade` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`class_id`),
  UNIQUE KEY `Index_2` (`code`),
  KEY `FK_ht_class_grade` (`grade`),
  CONSTRAINT `FK_ht_class_grade` FOREIGN KEY (`grade`) REFERENCES `ht_grade` (`grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------
--
-- 表的结构 `ht_guardian`
--
DROP TABLE IF EXISTS `ht_guardian`;
CREATE TABLE  `ht_guardian` (
  `guardian_id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `sexuality` smallint(1) unsigned default '1',
  `birthday` date default NULL,
  `national` varchar(45) default NULL,
  `id_card` varchar(45) default NULL,
  `phone` varchar(45) NOT NULL,
  `email` varchar(45) default NULL,
  `address` varchar(256) default NULL,
  `unit` varchar(45) default NULL,
  `student_id` int(10) unsigned default NULL,
  `student_code` int(10) default 0,
  `student_name` varchar(45) default NULL,
  `relationship` varchar(45) default NULL,
  `class_code` varchar(45) NOT NULL,
  `has_left` smallint(1) unsigned default '0',
  `is_active` smallint(1) unsigned default '1',
  `created` datetime NOT NULL,
  PRIMARY KEY  (`guardian_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- 表的结构 `ht_student`
--
CREATE TABLE  `ht_student` (
  `student_id` int(10) unsigned NOT NULL auto_increment,
  `code` INTEGER NOT NULL,
  `name` varchar(45) NOT NULL,
  `dorm` varchar(10) default '',
  `sexuality` smallint(1) unsigned default '1',
  `birthday` date default NULL,
  `national` varchar(20) default NULL,
  `id_card` varchar(45) default NULL,
  `phone` varchar(45) default NULL,
  `qq` varchar(20) default '',
  `email` varchar(45) default NULL,
  `address` varchar(256) default NULL,
  `class_code` varchar(45) NOT NULL,
  `guardian_id` int(10) unsigned default NULL,
  `guardian_name` varchar(45) NOT NULL,
  `guardian_relation` varchar(45) default NULL,
  `guardian_phone` varchar(45) NOT NULL,
  `password` varchar(32) default '',
  `license` varchar(45) default '',
  `has_left` smallint(1) unsigned default '0',
  `is_active` smallint(1) unsigned default '0',
  `memo` varchar(32) default '',
  `created` datetime NOT NULL,
  PRIMARY KEY  (`student_id`),
  UNIQUE KEY `Index_2` (`code`,`class_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `ht_course` (
  `course_id` int(10) unsigned NOT NULL auto_increment,
  `code` varchar(20) default '',
  `semster` varchar(45) default '',
  `z1_time` varchar(45) default '',
  `z1_w1` varchar(45) default '',
  `z1_w2` varchar(45) default '',
  `z1_w3` varchar(45) default '',
  `z1_w4` varchar(45) default '',
  `z1_w5` varchar(45) default '',
  `z1_w6` varchar(45) default '',
  `z1_w7` varchar(45) default '',
  `z2_time` varchar(45) default '',
  `z2_w1` varchar(45) default '',
  `z2_w2` varchar(45) default '',
  `z2_w3` varchar(45) default '',
  `z2_w4` varchar(45) default '',
  `z2_w5` varchar(45) default '',
  `z2_w6` varchar(45) default '',
  `z2_w7` varchar(45) default '',
  `1_time` varchar(45) default '',
  `1_w1` varchar(45) default '',
  `1_w2` varchar(45) default '',
  `1_w3` varchar(45) default '',
  `1_w4` varchar(45) default '',
  `1_w5` varchar(45) default '',
  `1_w6` varchar(45) default '',
  `1_w7` varchar(45) default '',
  `2_time` varchar(45) default '',
  `2_w1` varchar(45) default '',
  `2_w2` varchar(45) default '',
  `2_w3` varchar(45) default '',
  `2_w4` varchar(45) default '',
  `2_w5` varchar(45) default '',
  `2_w6` varchar(45) default '',
  `2_w7` varchar(45) default '',
  `3_time` varchar(45) default '',
  `3_w1` varchar(45) default '',
  `3_w2` varchar(45) default '',
  `3_w3` varchar(45) default '',
  `3_w4` varchar(45) default '',
  `3_w5` varchar(45) default '',
  `3_w6` varchar(45) default '',
  `3_w7` varchar(45) default '',
  `4_time` varchar(45) default '',
  `4_w1` varchar(45) default '',
  `4_w2` varchar(45) default '',
  `4_w3` varchar(45) default '',
  `4_w4` varchar(45) default '',
  `4_w5` varchar(45) default '',
  `4_w6` varchar(45) default '',
  `4_w7` varchar(45) default '',
  `5_time` varchar(45) default '',
  `5_w1` varchar(45) default '',
  `5_w2` varchar(45) default '',
  `5_w3` varchar(45) default '',
  `5_w4` varchar(45) default '',
  `5_w5` varchar(45) default '',
  `5_w6` varchar(45) default '',
  `5_w7` varchar(45) default '',
  `6_time` varchar(45) default '',
  `6_w1` varchar(45) default '',
  `6_w2` varchar(45) default '',
  `6_w3` varchar(45) default '',
  `6_w4` varchar(45) default '',
  `6_w5` varchar(45) default '',
  `6_w6` varchar(45) default '',
  `6_w7` varchar(45) default '',
  `7_time` varchar(45) default '',
  `7_w1` varchar(45) default '',
  `7_w2` varchar(45) default '',
  `7_w3` varchar(45) default '',
  `7_w4` varchar(45) default '',
  `7_w5` varchar(45) default '',
  `7_w6` varchar(45) default '',
  `7_w7` varchar(45) default '',
  `8_time` varchar(45) default '',
  `8_w1` varchar(45) default '',
  `8_w2` varchar(45) default '',
  `8_w3` varchar(45) default '',
  `8_w4` varchar(45) default '',
  `8_w5` varchar(45) default '',
  `8_w6` varchar(45) default '',
  `8_w7` varchar(45) default '',
  `w1_time` varchar(45) default '',
  `w1_w1` varchar(45) default '',
  `w1_w2` varchar(45) default '',
  `w1_w3` varchar(45) default '',
  `w1_w4` varchar(45) default '',
  `w1_w5` varchar(45) default '',
  `w1_w6` varchar(45) default '',
  `w1_w7` varchar(45) default '',
  `w2_time` varchar(45) default '',
  `w2_w1` varchar(45) default '',
  `w2_w2` varchar(45) default '',
  `w2_w3` varchar(45) default '',
  `w2_w4` varchar(45) default '',
  `w2_w5` varchar(45) default '',
  `w2_w6` varchar(45) default '',
  `w2_w7` varchar(45) default '',
  `w3_time` varchar(45) default '',
  `w3_w1` varchar(45) default '',
  `w3_w2` varchar(45) default '',
  `w3_w3` varchar(45) default '',
  `w3_w4` varchar(45) default '',
  `w3_w5` varchar(45) default '',
  `w3_w6` varchar(45) default '',
  `w3_w7` varchar(45) default '',
  `class_code` varchar(45) NOT NULL,
  PRIMARY KEY  (`course_id`)
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
  `notice_id` int(10) unsigned NOT NULL auto_increment,
  `type` smallint(1) unsigned default '0',
  `urgency` smallint(2) default '3',
  `class_code` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(45) default NULL,
  `is_active` smallint(1) default '1',
  `created` datetime NOT NULL,
  PRIMARY KEY  (`notice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `ht_notice_attach` (
  `attach_id` int(10) unsigned NOT NULL auto_increment,
  `notice_id` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `path` varchar(45) NOT NULL,
  `size` varchar(45) NOT NULL,
  `type` varchar(45) NOT NULL,
  `uploader` varchar(45) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`attach_id`)
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

DROP TABLE IF EXISTS `ht_forum`;
CREATE TABLE  `ht_forum` (
  `forum_id` int(10) unsigned NOT NULL auto_increment,
  `parent_id` int(11) default '0',
  `flow` int(11) default '0',
  `title` varchar(45) default NULL,
  `genus` smallint(2) default '0',
  `content` varchar(1024) NOT NULL,
  `is_active` smallint(1) default '1',
  `class_code` varchar(20) NOT NULL,
  `creator` varchar(45) NOT NULL,
  `user_id` int(10) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`forum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ht_exam_prj`;
CREATE TABLE  `ht_exam_prj` (
  `prj_id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `sdate` date default NULL,
  `edate` date default NULL,
  `class_code` varchar(20) NOT NULL,
  `closed` smallint(1) unsigned default '0',
  `created` datetime NOT NULL,
  PRIMARY KEY  (`prj_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `ht_exam` (
  `exam_id` int(10) unsigned NOT NULL auto_increment,
  `prj_id` int(10) unsigned NOT NULL,
  `class_code` varchar(45) NOT NULL,
  `subject` varchar(45) NOT NULL,
  `teacher` varchar(45) NOT NULL,
  `examdate` date NOT NULL,
  `stime` time NOT NULL,
  `etime` time NOT NULL,
  `classroom` varchar(45) NOT NULL,
  `closed` smallint(1) default '0',
  `created` varchar(45) NOT NULL,
  PRIMARY KEY  (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE  `ht_score` (
  `score_id` int(10) unsigned NOT NULL auto_increment,
  `prj_id` int(10) unsigned NOT NULL,
  `subject` varchar(45) NOT NULL,
  `student_code` int(10) NOT NULL,
  `score` float NOT NULL,
  `add_score` float default '0',
  `class_code` varchar(20) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`score_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ht_grade_rank`;
CREATE TABLE  `ht_grade_rank` (
  `grank_id` int(10) unsigned NOT NULL auto_increment,
  `prj_id` int(10) unsigned NOT NULL,
  `student_code` int(10) NOT NULL,
  `class_code` varchar(45) NOT NULL,
  `grade_rank` int(10) unsigned NOT NULL default '0',
  `up_down` int(11) NOT NULL default '0',
  `created` datetime NOT NULL,
  PRIMARY KEY  (`grank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ht_duty_item`;
CREATE TABLE  `ht_duty_item` (
  `duty_item_id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `genus` smallint(1) unsigned NOT NULL default '0',
  `score` int(10) default '0',
  `class_code` varchar(20) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`duty_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ht_duty`;
CREATE TABLE  `ht_duty` (
  `duty_id` int(10) unsigned NOT NULL auto_increment,
  `student_code` int(10) NOT NULL,
  `duty_item` varchar(45) NOT NULL,
  `score` int(11) NOT NULL default '0',
  `genus` smallint(1) unsigned default '0',
  `date_` date default NULL,
  `desc_` varchar(245) default NULL,
  `class_code` varchar(20) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`duty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;