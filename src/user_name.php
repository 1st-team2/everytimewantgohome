<?php

require_once( $_SERVER["DOCUMENT_ROOT"]."/config_nr.php");

function db_select_user_name(&$conn){
    //sql
    $sql = " SELECT user_name FROM select_img WHERE id = 1 ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}

function db_update_user_name(&$conn, &$array_param){
    //sql
    $sql = " UPDATE select_img SET user_name = :user_name WHERE id = 1 ";

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    //return
    return $stmt->rowCount();
}

try {
    
    $conn = new PDO(MARIADB_DSN, MARIADB_USER, MARIADB_PASSWORD);

    if(REQUEST_METHOD == "POST"){

        // 파라미터 가져오기
        $user_name = isset($_POST["user_name"]) ? $_POST["user_name"] : "적용안됨";

        //Transaction 시작
        $conn->beginTransaction();

        //이름 수정
        $arr_param = [
            "user_name" => $user_name
        ];
        
        $result = db_update_user_name($conn, $arr_param);

        $conn->commit();

        header("Location:main01.php");

    }
} catch (PDOException $e) {
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