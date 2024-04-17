<?php

//db커넥 함수
function my_db_conn() {
    $option = [																		
        PDO::ATTR_EMULATE_PREPARES			=>   FALSE							
        ,PDO::ATTR_ERRMODE					=>  PDO::ERRMODE_EXCEPTION							
        ,PDO::ATTR_DEFAULT_FETCH_MODE		=>  PDO::FETCH_ASSOC							
    ];																		
    
    return new PDO(MARIADB_DSN, MARIADB_USER, MARIADB_PASSWORD, $option);
}

//deleted_at가 null인 게시글을 불러오는 함수
function db_select_boards_cnt(&$conn) {
    $sql =
    "SELECT	"
    ." 	COUNT(no) as cnt "
    ." FROM	"
    ." 	boards "
    ." WHERE "
    ." 	deleted_at IS NULL "
    ;
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll();

    return (int)$result[0]["cnt"];
}


//게시판 테이블 레코드 작성처리
function db_insert_boards(&$conn, &$array_param) {
    $sql =
        " INSERT INTO boards( "
        ." user_id, title "
        ." ,content "
        ." ,target_date"
        ." ) "
        ." VALUES( "
        ."  1, :title "
        ." ,:content "
        ." ,:target_date "
        ." ) "
        ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    return $stmt->rowCount();
}				

//특정 게시글 삭제 처리
function db_delete_boards_no(&$conn, &$array_param) {
    $sql =
    " UPDATE boards "
    ." SET "
    ."  deleted_at = NOW() "
    ." WHERE "
    ."  no = :no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    return $stmt->rowCount();
}

//특정 게시글 수정처리
function db_update_boards_no(&$conn, &$array_param) {
    $sql =
        " UPDATE boards "
        ." SET "
        ." title = :title "
        ." ,content = :content "
        ." ,updated_at = NOW() "
        ." WHERE "
        ." no = :no "
    ;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    
    return $stmt->rowCount();
}


// 체크확인
function db_update_contents_checked_at(&$conn, &$array_param) {
    $sql =
        " UPDATE boards "
        ." SET "
        ." checked_at = CASE WHEN " 
        ." checked_at IS NULL THEN NOW() ELSE NULL END "
        ." WHERE " 
        ." no = :no "
    ;
    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    // 리턴
    return $stmt->rowCount();
}
//update : 노경호 0409
function db_select_boards_paging(&$conn, &$array_param) {
    
    $sql =     
        "SELECT	"
            ." no "
            ." ,title "
            ." ,created_at "
            ." ,checked_at "
            ." FROM "
            ."  boards "
            ." WHERE "
            ."  deleted_at IS NULL "
            ." AND DATE(target_date) = :target_date "
            ." ORDER BY "
            ." checked_at " 
            ." ,created_at DESC "
            ." LIMIT :list_cnt "
            // ." LIMIT :list_cnt OFFSET :offset "
        ;
    
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();
   
    return $result;

}

// pk로 게시글 정보 조회
function db_select_boards_no(&$conn, &$array_param) {
    // sql작성
    $sql = 
    " SELECT "
    ."  no "
    ."  ,title "
    ."  ,content "
    ."  ,created_at "
    ." FROM "
    ."  boards "
    ." WHERE "
    ."  no = :no "
;

    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();

    // 리턴
    return $result;
}

// gh - 목표 달성 처리 함수
function achieve_goal($goal_id, $conn) {
    $sql = "UPDATE boards SET checked_at = curdate() WHERE no = :no ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(':id', $goal_id, PDO::PARAM_INT);
    $stmt->execute();
    return true;
}

// gh - 사용자가 달성한  오늘 목표의 수를 가져오는 함수
function get_today_achieved_count($conn) {
    $sql = "SELECT COUNT(*) AS count FROM boards WHERE checked_at is not null and deleted_at IS NULL and DATE(target_date) = CURDATE() ";
    $stmt = $conn->query($sql);
    $row = $stmt->fetch();
    return $row["count"];
}

// gh - 오늘 목표 수를 가져오는 함수
function get_today_goals_count($conn) {
    $sql = "SELECT COUNT(*) AS count FROM boards where deleted_at IS NULL and date(target_date) = CURDATE() ";
    $stmt = $conn->query($sql);
    $row = $stmt->fetch();
    return $row["count"];
}

//이번 주 달성한 목표를 가져오는 함수
function get_week_achieved_count($conn) {
    try {
        // 현재 주간의 시작 (일요일)
        $start_of_week = date('Y-m-d', strtotime('last sunday'));
        // 현재 주간의 끝 (토요일)
        $end_of_week = date('Y-m-d', strtotime('next saturday')); 

        $sql = "SELECT COUNT(*) AS count FROM boards WHERE checked_at IS NOT NULL and deleted_at IS NULL AND target_date BETWEEN :start_of_week AND :end_of_week";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':start_of_week', $start_of_week);
        $stmt->bindValue(':end_of_week', $end_of_week);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row["count"];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0; // 에러 발생 시 0을 반환하거나 적절한 처리를 수행
    }
}

// 주간 전체 목표 수를 가져오는 함수
function get_week_goals_count($conn) {
    try {
        // 현재 주간의 시작 (일요일)
        $start_of_week = date('Y-m-d', strtotime('last sunday'));
        // 현재 주간의 끝 (토요일)
        $end_of_week = date('Y-m-d', strtotime('next saturday'));

        $sql = "SELECT COUNT(*) AS count FROM boards WHERE deleted_at IS NULL and target_date BETWEEN :start_of_week AND :end_of_week";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':start_of_week', $start_of_week);
        $stmt->bindValue(':end_of_week', $end_of_week);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row["count"];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0; // 에러 발생 시 0을 반환하거나 적절한 처리를 수행
    }
}

//이번달 달성한 목표 수를 가져오는 함수
function get_month_achieved_count($conn) {
    try {
        $current_month = date('m'); // 현재 월 가져오기

        $sql = "SELECT COUNT(*) AS count FROM boards WHERE deleted_at IS NULL and checked_at IS NOT NULL AND MONTH(target_date) = :current_month";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':current_month', $current_month, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row["count"];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0; // 에러 발생 시 0을 반환하거나 적절한 처리를 수행
    }
}

// 이번 달 전체 목표 수를 가져오는 함수
function get_month_goals_count($conn) {
    try {
        $current_month = date('m'); // 현재 월 가져오기

        $sql = "SELECT COUNT(*) AS count FROM boards WHERE deleted_at IS NULL and MONTH(target_date) = :current_month";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':current_month', $current_month, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row["count"];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0; // 에러 발생 시 0을 반환하거나 적절한 처리를 수행
    }
}

// nr - 닉네임 , 아바타이미지 가져오는 함수
function db_select_name_img(&$conn){
    // sql
    $sql = " SELECT user_name, avatar FROM users WHERE id = 1 ";

    $stmt= $conn->prepare($sql);
    $stmt->execute();
    $result= $stmt->fetchAll();
    return $result ;
}

// nr - 이미지 업데이트 함수
function db_update_image(&$conn, &$arr_param){
    //sql
    $sql = " UPDATE users SET avatar = :avatar WHERE id = 1 ";

    //query start
    $stmt = $conn->prepare($sql);
    $stmt->execute($arr_param);

    //return
    return $stmt->rowCount();
}

// nr - 닉네임 업데이트 함수
function db_update_user_name(&$conn, &$array_param){
    //sql
    $sql = " UPDATE users SET user_name = :user_name WHERE id = 1 ";

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    //return
    return $stmt->rowCount();
}

