CREATE TABLE `users` (
	`userId` INT(25) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`username` VARCHAR(65) NOT NULL ,
	`password` VARCHAR(32) NOT NULL ,
	`email` VARCHAR(255) NOT NULL
);

CREATE TABLE following
(
    id       INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    userId	 INTEGER NOT NULL,
    followingName VARCHAR(255) NOT NULL,
    followingId INTEGER NOT NULL
);