<?php

require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); //DB 관련 라이브러리 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {
    // PDO 객체 생성하여 데이터베이스에 연결
    $conn = my_db_conn();
    // POST 방식으로 요청이 왔을 때만 실행
    if(REQUEST_METHOD == "POST"){

        // 파라미터 가져오기
        // POST로 전달된 파라미터 가져오기, 없으면 "적용안됨"으로 설정
        $user_name = isset($_POST["user_name"]) ? $_POST["user_name"] : "적용안됨";

        //Transaction 시작
        $conn->beginTransaction();

        //이름 수정
        // 사용자 이름 업데이트를 위한 매개변수 배열 생성
        $arr_param = [
            "user_name" => $user_name
        ];
        // 사용자 이름 업데이트 함수 호출
        $result = db_update_user_name($conn, $arr_param);

        $conn->commit(); // 트랜잭션 커밋

        header("Location:main01.php"); // 메인 페이지로 리다이렉트

    }
} catch (PDOException $e) {
    // 트랜잭션이 진행 중이면 롤백
    if(!empty($conn) && $conn->inTransaction()){
        $conn->rollBack();
    }
    echo $e->getMessage(); // 예외 메시지 출력 후 종료
    exit;

} finally {
    if(!empty($conn)){ // 연결이 열려 있으면 연결 종료
        $conn = null;
    }
}