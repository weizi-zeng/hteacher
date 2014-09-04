alter table hteacher.ht_admin_user add column name varchar(45) default '' after user_name;

alter table ht_guardian add column student_code varchar(20) default '' after student_id;