CREATE DATABASE todo_list;

USE todo_list;

CREATE TABLE todo_list_info(
            list_no INT PRIMARY KEY AUTO_INCREMENT
            ,list_title VARCHAR(100) NOT NULL
            ,list_detail VARCHAR(1000)
            ,list_start_date DATETIME NOT NULL
            ,list_due_date DATETIME NOT NULL
            ,list_clear_date DATETIME 
            ,list_clear_flg CHAR(1) NOT NULL DEFAULT('0')
            ,list_imp_flg CHAR(1) NOT NULL DEFAULT('0')
            );

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
;

INSERT INTO goals (goal_name,achieved)
VALUE ('test-15', 0 )
		,('test-16', 0 )
		,('test-17', 0 )
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

