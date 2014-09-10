alter table hteacher.ht_admin_user add column name varchar(45) default '' after user_name;

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
