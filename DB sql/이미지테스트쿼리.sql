CREATE DATABASE tng_test;
USE tng_test;

CREATE TABLE select_img (
	id			INT				PRIMARY KEY	AUTO_INCREMENT
	,img		VARCHAR(100)	NOT NULL		DEFAULT '/image/ex.jpg'
);

INSERT INTO select_img (
	img
)
VALUE('/image/ex.jpg');