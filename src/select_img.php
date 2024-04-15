<?php 
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {

    $conn = my_db_conn();

    if(REQUEST_METHOD == "POST") {
        // var_dump($_POST["img"]);
        $img = isset($_POST["avatar"]) ? trim($_POST["avatar"]) : "/image/avatar01.png";          
        //Transaction 시작
        $conn->beginTransaction();

        //이미지패스 수정
        $arr_param = [
            "avatar" => $img
        ];

        $result = db_update_image($conn, $arr_param);

        $conn->commit();

        $item = $result;

        header("Location:main01.php");
    }
} catch (\PDOException $e) {
    if(!empty($conn) && $conn->inTransaction()){
        $conn->rollBack();
    }
    echo $e->getMessage();
    exit;
} finally {
    if(!empty($conn)){
        $conn = null;
    }
}