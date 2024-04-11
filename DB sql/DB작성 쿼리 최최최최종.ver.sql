CREATE DATABASE pixel_forest;

CREATE TABLE users(
		id				INT				AUTO_INCREMENT			PRIMARY KEY 
		,user_name			VARCHAR(100)	NOT NULL			DEFAULT '이름'
		,avatar		VARCHAR(200)	NOT NULL			DEFAULT '/image/ex.jpg'
);

CREATE TABLE boards(
		no			INT				AUTO_INCREMENT			PRIMARY KEY 
		,user_id			INT				NOT NULL	
		,title			VARCHAR(200)	NOT NULL	
		,content			VARCHAR(1000)	NOT NULL	
		,created_at		DATETIME			NOT NULL 				DEFAULT CURRENT_TIMESTAMP()
		,target_at		DATE				NOT NULL 				
		,updated_at		DATETIME			NOT NULL					DEFAULT CURRENT_TIMESTAMP()
		,deleted_at		DATETIME	
		,checked_at		DATETIME	
);

ALTER TABLE boards
ADD CONSTRAINT fk_user_id
FOREIGN KEY (user_id) REFERENCES users(id);

-- ----------------------------------------------------
-- 데이터 삽입 예시
-- INSERT INTO boards (user_id, title, content, target_at)
-- VALUES (1, 'Example Title', 'Example Content', (SELECT DATE(created_at) FROM boards WHERE board_no = 1));



