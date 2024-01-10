CREATE TABLE `admin` (
	`ad_id` INT(11) NOT NULL AUTO_INCREMENT,
	`ad_address` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`ad_phone` INT(11) NOT NULL,
	`ad_email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`ad_username` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`ad_password` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`ad_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;

CREATE TABLE `brand` (
	`brand_id` INT(10) NOT NULL AUTO_INCREMENT,
	`brand_name` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`brand_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `employee` (
	`emp_id` INT(11) NOT NULL AUTO_INCREMENT,
	`ad_id` INT(11) NOT NULL,
	`emp_fname` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`emp_lname` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`emp_address` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`emp_phone` INT(11) NOT NULL,
	`emp_email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`emp_username` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`emp_password` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`emp_status` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`emp_id`) USING BTREE,
	INDEX `admin_id_fk` (`ad_id`) USING BTREE,
	CONSTRAINT `admin_id_fk` FOREIGN KEY (`ad_id`) REFERENCES `admin` (`ad_id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;

CREATE TABLE `inventory` (
	`inv_id` INT(10) NOT NULL AUTO_INCREMENT,
	`inv_item_qty` INT(10) NOT NULL,
	`prod_id` INT(10) NOT NULL,
	PRIMARY KEY (`inv_id`) USING BTREE,
	INDEX `fk_invprod` (`prod_id`) USING BTREE,
	CONSTRAINT `fk_invprod` FOREIGN KEY (`prod_id`) REFERENCES `product` (`prod_id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `invoice` (
	`invo_id` INT(10) NOT NULL AUTO_INCREMENT,
	`invo_date` DATE NOT NULL,
	`invo_amnt` DECIMAL(65,0) NOT NULL,
	`invo_stat` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`emp_id` INT(10) NOT NULL,
	PRIMARY KEY (`invo_id`) USING BTREE,
	INDEX `fk_emp` (`emp_id`) USING BTREE,
	CONSTRAINT `fk_emp` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`emp_id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `orderlist` (
	`order_id` INT(10) NOT NULL AUTO_INCREMENT,
	`prod_id` INT(10) NOT NULL,
	`invo_id` INT(10) NOT NULL,
	`order_qty` INT(100) NOT NULL,
	`order_stat` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`order_price` DECIMAL(65,0) NOT NULL,
	PRIMARY KEY (`order_id`) USING BTREE,
	INDEX `fk_prod` (`prod_id`) USING BTREE,
	INDEX `fk_invo_ord` (`invo_id`) USING BTREE,
	CONSTRAINT `fk_invo_ord` FOREIGN KEY (`invo_id`) REFERENCES `invoice` (`invo_id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_prod` FOREIGN KEY (`prod_id`) REFERENCES `product` (`prod_id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `product` (
	`prod_id` INT(10) NOT NULL AUTO_INCREMENT,
	`prod_desc` INT(10) NOT NULL,
	`prod_price` DECIMAL(65,0) NOT NULL,
	`prod_name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
	`brand_id` INT(10) NOT NULL,
	PRIMARY KEY (`prod_id`) USING BTREE,
	INDEX `fk_brand_id` (`brand_id`) USING BTREE,
	CONSTRAINT `fk_brand_id` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `requistion` (
	`req_id` INT(10) NOT NULL AUTO_INCREMENT,
	`req_prod` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`req_desc` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`req_brand` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
	`req_quantity` INT(10) NOT NULL,
	`req_item_price` DECIMAL(65,0) NOT NULL,
	`req_status` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`sup_id` INT(10) NOT NULL,
	`brand_id` INT(10) NOT NULL,
	PRIMARY KEY (`req_id`) USING BTREE,
	INDEX `fk_req_sup` (`sup_id`) USING BTREE,
	INDEX `fk_req_brand` (`brand_id`) USING BTREE,
	CONSTRAINT `fk_req_brand` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_req_sup` FOREIGN KEY (`sup_id`) REFERENCES `supplier` (`sup_Id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `supplier` (
	`sup_Id` INT(10) NOT NULL AUTO_INCREMENT,
	`sup_type` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`sup_email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
	`sup_phone` INT(15) NOT NULL,
	PRIMARY KEY (`sup_Id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

