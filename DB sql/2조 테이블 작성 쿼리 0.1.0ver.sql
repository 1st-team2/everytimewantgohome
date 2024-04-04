CREATE DATABASE todolist;

USE todolist;

CREATE TABLE users(
	id					INT									PRIMARY KEY AUTO_INCREMENT
	,user_id			VARCHAR(50)		NOT NULL			UNIQUE
	,user_pw			VARCHAR(50)		NOT NULL	
	,user_name		VARCHAR(30)		NOT NULL	
	,birth_date		DATE				NOT NULL
	,created_at		DATETIME			NOT NULL			DEFAULT CURRENT_TIMESTAMP	
	,updateD_at		DATETIME			NOT NULL			DEFAULT CURRENT_TIMESTAMP	ON UPDATE	CURRENT_TIMESTAMP
	,deleted_at		DATETIME			NULL
	);
	
	
CREATE TABLE boards(
	no					INT									PRIMARY KEY AUTO_INCREMENT
	,user_id			INT				NOT NULL			
	,date_at			DATE()			NOT NULL
	,title			VARCHAR(50)		NOT NULL
	,content			VARCHAR(1000)	NOT NULL
	,created_at		DATETIME			NOT NULL			DEFAULT CURRENT_TIMESTAMP
	,deleted_at		DATETIME			NULL				DEFAULT CURRENT_TIMESTAMP
	,done				INT				NOT NULL			DEFAULT 0
	FOREIGN KEY (user_id) REFERENCES users(id)
	);