CREATE TABLE `asc_list_door` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`asc_name` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`asc_area` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`updatetime` TIMESTAMP NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
	`createtime` TIMESTAMP NULL DEFAULT (now()),
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `asc_name` (`asc_name`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=5
;


CREATE TABLE `scan_logs` (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`cardid` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
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
	INDEX `cardid` (`cardid`) USING BTREE,
	INDEX `createtime` (`createtime`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=28
;
