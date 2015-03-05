CREATE TABLE users (
	"id" SERIAL PRIMARY KEY ,
	"username" VARCHAR(65) NOT NULL ,
	"password" VARCHAR(32) NOT NULL
);

CREATE TABLE followings
(
    id       SERIAL PRIMARY KEY,
    userId	 INT NOT NULL,
    followingName VARCHAR(255) NOT NULL,
    followingId INTEGER NOT NULL
);

INSERT INTO users (username, password) VALUES ('admin', MD5('admin22'));