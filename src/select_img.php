<?php 
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(FILE_LIB_DB); // DB관련 라이브러리


// function db_select_img(&$conn, $arr_param) {
//     //sql
//     $sql = " SELECT avatar FROM users WHERE id = 1 ";

//     $stmt = $conn->prepare($sql);
//     $stmt->execute($arr_param);
//     $result = $stmt->fetchAll();
//     return $result;
// }

// function db_update_image(&$conn, &$arr_param){
//     //sql
//     $sql = " UPDATE users SET avatar = :avatar WHERE id = 1 ";

//     //query start
//     $stmt = $conn->prepare($sql);
//     $stmt->execute($arr_param);

//     //return
//     return $stmt->rowCount();
// }

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

        //예외처리
        // if($result !== 1){
        //     throw new Exception("Update Boards no count");
        // }
        //commit
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