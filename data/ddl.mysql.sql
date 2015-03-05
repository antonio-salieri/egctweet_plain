CREATE TABLE `user` (
	`id` INT(25) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`username` VARCHAR(65) NOT NULL ,
	`password` VARCHAR(32) NOT NULL
);

CREATE TABLE following
(
    id       INT(25) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userId	 INT(25) NOT NULL,
    followingName VARCHAR(255) NOT NULL,
    followingId INTEGER NOT NULL
);

INSERT INTO users (username, password) VALUES ('admin', MD5('admin22'));