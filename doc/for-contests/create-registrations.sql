use online_judge;

CREATE TABLE  `online_judge`.`registrations` (
`username` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`report_id` BIGINT NOT NULL ,
PRIMARY KEY (  `username` ,  `report_id` )
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE registrations (
  username VARCHAR( 30 ) NOT NULL,
  report_id BIGINT NOT NULL,
  PRIMARY KEY ( username, report_id )
);
