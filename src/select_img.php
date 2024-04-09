<?php 
require_once( $_SERVER["DOCUMENT_ROOT"]."/config_nr.php");

function db_select_img(&$conn, $arr_param) {
    //sql
    $sql = " SELECT img FROM select_img WHERE id = 1 ";

    $stmt = $conn->prepare($sql);
    $stmt->execute($arr_param);
    $result = $stmt->fetchAll();
    return $result;
}

function db_update_image(&$conn, &$arr_param){
    //sql
    $sql = " UPDATE select_img SET img = :img WHERE id = 1 ";

    //query start
    $stmt = $conn->prepare($sql);
    $stmt->execute($arr_param);

    //return
    return $stmt->rowCount();
}

try {

    $conn = new PDO(MARIADB_DSN, MARIADB_USER, MARIADB_PASSWORD);

    if(REQUEST_METHOD == "POST") {
        // var_dump($_POST["img"]);
        $img = isset($_POST["img"]) ? trim($_POST["img"]) : "/image/personal.png";          
        //Transaction 시작
        $conn->beginTransaction();

        //이미지패스 수정
        $arr_param = [
            "img" => $img
        ];

        $result = db_update_image($conn, $arr_param);

        //예외처리
        // if($result !== 1){
        //     throw new Exception("Update Boards no count");
        // }
        //commit
        $conn->commit();

        $item = $result;

        header("Location:main01_nr.php");
    }
} catch (\Throwable $e) {
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