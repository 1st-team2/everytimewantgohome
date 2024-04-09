<?php

function my_db_conn() {
    // 설정 정보
    $option = [
        PDO::ATTR_EMULATE_PREPARES      => FALSE
        ,PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION
        ,PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC
    ];

    // 리턴
    return new PDO(MARIADB_DSN, MARIADB_USER, MARIADB_PASSWORD, $option);
}

function db_select_boards_cnt(&$conn) {
    // sql 작성
    $sql =
        " SELECT "
        ."  COUNT(no) as cnt "
        ." FROM	"
        ."  boards "
        ." WHERE "
        ."  deleted_at IS NULL "
    ;

    // Query 실행
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll();

    // 리턴
    return (int)$result[0]["cnt"];
}



function db_select_boards_paging(&$conn, &$array_param) {
    // sql 작성
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
            ." ORDER BY "
            ." checked_at " 
            ." ,created_at DESC "
            ." LIMIT :list_cnt OFFSET :offset "
        ;

    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);
    $result = $stmt->fetchAll();

    // 리턴
    return $result;
}

			
// ■ Insert row to boards 게시판 테이블 레코드 작성철
function db_insert_boards(&$conn, &$array_param){
    // SQL 작성
    $sql =
        " INSERT INTO boards( "
        ." title "
        ."  ,content "
        ." ) "
        ." VALUES( "
        ."  :title "
        ."  ,:content "
        ." ) "
    ;

    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    return $stmt->rowCount();
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


// pk로 특정 게시글 삭제 처리
function db_delete_boards_no(&$conn, &$array_param) {
    // SQL
    $sql =
        " UPDATE boards "
        ." SET "
        ."  deleted_at = NOW() "
        ." WHERE "
        ."  no = :no "
    ;

    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    // 리턴
    return $stmt->rowCount();
}

// pk로 특정 레코드 수정
function db_update_boards_no(&$conn, &$array_param) {
    // SQL
    $sql =
        " UPDATE boards "		
        ." SET "	
        ."  title = :title "	
        ."  ,content = :content "
        ."  ,updated_at = NOW() "
        // 체크한 게시물 수정시 체크해제
        // ." ,checked_at = NULL "
        ." WHERE "		
        ."  no = :no "	
    ;
    
    // Query 실행
    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    // 리턴
    return $stmt->rowCount();
}


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