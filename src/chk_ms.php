<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {
    $conn = my_db_conn();
    // POST로 전달된 데이터를 변수에 할당, 없을 경우 빈 문자열로 초기화
    $no = isset($_POST["no"]) ? $_POST["no"] : "";
    $page = isset($_POST["page"]) ? $_POST["page"] : "";
    $date = isset($_POST["date"]) ? $_POST["date"] : "";
    // 변경할 내용의 번호를 포함한 매개변수 배열 생성
    $arr_param = [
        "no" => $no
    ];
    // 트랜잭션 시작
    $conn->beginTransaction();
    // 함수 호출
    $result = db_update_contents_checked_at($conn, $arr_param);
    // 트랜잭션 커밋
    $conn->commit();

    // 상세 페이지로 이동
    header("Location: list.php?date={$date}");

// 예외 발생 시 메시지 출력 후 종료
} catch(\Throwable $e) {
    echo $e->getMessage();
    exit;
// 연결이 열려 있는 경우 연결 닫기
} finally {
    if(!empty($conn)) {
        $conn = null;
    }
}