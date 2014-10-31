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
 
 