CREATE TABLE "user" (
	"id" SERIAL PRIMARY KEY ,
	"username" VARCHAR(65) NOT NULL ,
	"password" VARCHAR(32) NOT NULL
);

CREATE TABLE following
(
    id       SERIAL PRIMARY KEY,
    userId	 INT NOT NULL,
    followingName VARCHAR(255) NOT NULL,
    followingId INTEGER NOT NULL
);