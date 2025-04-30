CREATE TABLE `asc_list_door` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`asc_name` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`asc_area` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`cjihao` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`mjihao` INT NULL DEFAULT NULL,
	`updatetime` TIMESTAMP NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
	`createtime` TIMESTAMP NULL DEFAULT (now()),
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `asc_name` (`asc_name`) USING BTREE,
	INDEX `cjihao` (`cjihao`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0
;


CREATE TABLE `scan_logs` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`cardid` VARCHAR(250) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`cjihao` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`mjihao` INT NULL DEFAULT NULL,
	`status` INT NULL DEFAULT NULL,
	`time` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`output` INT NULL DEFAULT NULL,
	`code` INT NULL DEFAULT NULL,
	`message` VARCHAR(200) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`rs_success` VARCHAR(200) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`rs_message` VARCHAR(200) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`createtime` TIMESTAMP NULL DEFAULT (now()),
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `createtime` (`createtime`) USING BTREE,
	INDEX `cardid` (`cardid`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0
;


CREATE TABLE `user` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`password` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`role_id` INT NULL DEFAULT NULL,
	`prefix` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`firstname` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`lastname` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`phone` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`active` TINYINT NULL DEFAULT '0',
	`lastlogin` DATETIME NULL DEFAULT NULL,
	`updatetime` TIMESTAMP NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
	`createtime` TIMESTAMP NULL DEFAULT (now()),
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `username` (`username`) USING BTREE,
	INDEX `active` (`active`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0
;



CREATE TABLE `user_role` (
	`role_id` INT NOT NULL AUTO_INCREMENT,
	`role_name` VARCHAR(250) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`role_active` TINYINT NULL DEFAULT '0',
	`updatetime` TIMESTAMP NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
	`createtime` TIMESTAMP NULL DEFAULT (now()),
	PRIMARY KEY (`role_id`) USING BTREE,
	INDEX `role_active` (`role_active`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0
;

CREATE TABLE `member` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`firstname` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`lastname` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`phone` VARCHAR(10) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`email` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`position` VARCHAR(250) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`startdate` TIMESTAMP NULL DEFAULT (now()),
	`enddate` DATETIME NULL DEFAULT NULL,
	`cardnumber` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`active` TINYINT NULL DEFAULT '0',
	`status` TINYINT NULL DEFAULT '1',
	`updateby` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`createby` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`updatetime` TIMESTAMP NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
	`creatime` TIMESTAMP NULL DEFAULT (now()),
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `active` (`active`) USING BTREE,
	INDEX `position` (`position`) USING BTREE,
	INDEX `id_key` (`id`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0
;
