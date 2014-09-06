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

