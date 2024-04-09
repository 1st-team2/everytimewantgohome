<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {
    $conn = my_db_conn();

    $no = isset($_POST["no"]) ? $_POST["no"] : "";
    $page = isset($_POST["page"]) ? $_POST["page"] : "";

    $arr_param = [
        "no" => $no
    ];
    $conn->beginTransaction();
    $result = db_update_contents_checked_at($conn, $arr_param);
    $conn->commit();

    // 상세 페이지로 이동
    header("Location: list.php");


} catch(\Throwable $e) {
    echo $e->getMessage();
    exit;

} finally {
    if(!empty($conn)) {
        $conn = null;
    }
}