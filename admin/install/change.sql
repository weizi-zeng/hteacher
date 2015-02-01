alter table hteacher.ht_admin_user add column name varchar(45) default '' after user_name;
alter table hteacher.ht_admin_user add column memo varchar(32) default '' ;

alter table ht_guardian add column student_code varchar(20) default '' after student_id;

DROP TABLE IF EXISTS `hteacher`.`ht_subject`;
CREATE TABLE  `hteacher`.`ht_subject` (
  `subject_id` int(10) unsigned NOT NULL auto_increment,
  `subject` varchar(20) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `hteacher`.`ht_education` (
  `education_id` int(10) unsigned NOT NULL auto_increment,
  `type` smallint(5) unsigned NOT NULL default '1',
  `title` varchar(225) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(54) NOT NULL,
  `is_active` smallint(5) unsigned NOT NULL default '1',
  `school_code` varchar(45) NOT NULL,
  `class_code` varchar(45) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`education_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `hteacher`.`ht_license` CHANGE COLUMN `is_active` `state` SMALLINT(1) UNSIGNED DEFAULT 0;
ALTER TABLE `hteacher`.`ht_license` MODIFY COLUMN `state` SMALLINT(1) DEFAULT 0,
 ADD COLUMN `pay_id` INTEGER UNSIGNED DEFAULT 0 AFTER `regtime`,
 ADD COLUMN `student_id` INTEGER UNSIGNED DEFAULT 0 AFTER `pay_id`,
 ADD COLUMN `class_code` VARCHAR(45) DEFAULT 0 AFTER `student_id`,
 ADD COLUMN `school_code` VARCHAR(45) DEFAULT '' AFTER `class_code`,
 ADD COLUMN `memo` VARCHAR(128) DEFAULT '' AFTER `school_code`;
 
 update ht_feedback set msg_type=0;
 
 --整合 表 ht_problems 和表  ht_education ，重新创建文章表
CREATE TABLE  `hteacher`.`ht_article` (
  `article_id` mediumint(8) unsigned NOT NULL auto_increment,
  `cat_id` smallint(5) NOT NULL default '0',
  `title` varchar(150) NOT NULL default '',
  `content` longtext NOT NULL,
  `author` varchar(30) NOT NULL default '',
  `author_email` varchar(60) default NULL,
  `keywords` varchar(255) default NULL,
  `article_type` tinyint(1) unsigned default '2',
  `is_open` tinyint(1) unsigned default '1',
  `add_time` int(10) unsigned NOT NULL default '0',
  `file_url` varchar(255) default NULL,
  `open_type` tinyint(1) unsigned default '0',
  `link` varchar(255) default NULL,
  `description` varchar(255) default NULL,
  `is_important` tinyint(1) default '0',
  `view_count` int(10) unsigned default '0',
  `school_code` varchar(45) default '',
  `class_code` varchar(45) default '',
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`article_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


 
 
 