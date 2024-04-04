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
        ." title "
        ." ,content "
        ." ) "
        ." VALUES( "
        ."  :title "
        ." ,:content "
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