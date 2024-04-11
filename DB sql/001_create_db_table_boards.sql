
USE todolist;

CREATE TABLE boards (
	no 				INT 				PRIMARY KEY AUTO_INCREMENT
	,title 			VARCHAR(100) 	NOT NULL
	,content 		VARCHAR(1000) 	NOT NULL
	,checked_at		DATETIME
	,created_at 	DATETIME 		
	
	,updated_at 	DATETIME 		NOT NULL	DEFAULT 	CURRENT_TIMESTAMP()
	,deleted_at 	DATETIME
);


ALTER TABLE boards
ADD COLUMN target_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() AFTER created_at;

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