CREATE DATABASE todolist;

USE todolist;

CREATE TABLE users(
	id					INT									PRIMARY KEY AUTO_INCREMENT
	,NAME				VARCHAR(100)						NOT NULL	     						DEFAULT '홍길동'
	,image			VARCHAR(100)						NOT NULL 							DEFAULT '/image/personal.png'
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
	
	
	

-- 퍼센트 게이지 구현을 위한 임시 쿼리
CREATE TABLE goals (
 id INT AUTO_INCREMENT PRIMARY KEY,
 goal_name VARCHAR(255) NOT NULL,
 target_value INT NOT NULL,
 achieved INT DEFAULT 0  -- 목표 달성 여부 (0: 달성 전, 1: 달성)
);

INSERT INTO goals (goal_name,achieved)
VALUE ('test-1', 0 )
		,('test-2', 1 )
		,('test-3', 0 )
		,('test-4', 0 )
		,('test-5', 1 )
		,('test-6', 0 )
		,('test-7', 1 )
		,('test-8', 0 )
		,('test-9', 0 )
		,('test-10', 0 )
		,('test-11', 1 )
		,('test-12', 1 )
		,('test-13', 1 )
		,('test-14', 1 )
		,('test-15', 0 )
		,('test-16', 1 )
		,('test-17', 1 )
		,('test-18', 0 )
		,('test-19', 0 )
		,('test-20', 0 )
		,('test-21', 0 )
		,('test-22', 0 )
		,('test-23', 0 )
		,('test-24', 0 )
		,('test-25', 0 )
		,('test-26', 0 )
		,('test-27', 0 )
		,('test-28', 0 )
;



-- ------------------------------------------------------------

-- 라디오 버튼 관련 db작성

CREATE TABLE users(
		id				INT						PRIMARY KEY AUTO_INCREMENT 	
		,NAME			VARCHAR(100)			NOT NULL								DEFAULT '이름'
		,avatar		VARCHAR(500)			NOT NULL								DEFAULT '/image/ex.jpg'
);
