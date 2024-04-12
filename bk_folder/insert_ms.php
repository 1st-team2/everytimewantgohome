 <?php

// <!-- 
// v. 1.0.1
// 작성일자 : 2024-04-05 오전 11시
// 작성(수정)자 : 권민서
// 작성(수정)내용 : div클래스명 규칙

// 클래스명
// 1. 'TODO LIST' : header
// 2. 창 부분 : main
// 3. 창 상단 최소화,전체화면,뒤로가기(이미지수정)부분 : main_top
// 3-1. 날짜 들어갈곳 : top_date
// 3-2. 최소화(-) : minus
// 3-3. 네모(ㅁ) : square
// 3-4. 뒤로가기(x) : back 
// 4. 창 화면 부분 : main_mid
// 5. 게이지,달력 부분 : main_left
// 6. 이름,프사 부분 : main_right
// 7. 게이지 이름 : gauge_name
// 8. 게이지 부분 : gauge_bar
// 9. 달력 이름 : cal_name
// 10. 달력 : cal
// 11. 닉네임 부분 : nick_name
// 12. 프사부분 : personal_img
// -->

// 설정 정보
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');


//현재날짜 가져오기
$current_date = date('Y-m-d');

// 이전 날짜 계산 (하루 전)
$previous_date = date('Y-m-d', strtotime($date . ' -1 day'));

// 다음 날짜 계산 (하루 후)
$next_date = date('Y-m-d', strtotime($date . ' +1 day'));

// // GET 
// if(REQUEST_METHOD === "GET") {
//     // GET처리
// } 분기를 나누고 아무 처리없으면 지워져도 괜찮다
if(REQUEST_METHOD === "POST") {
    try {
        // 파라미터 획득
        $title = isset($_POST["title"]) ? trim($_POST["title"]) : ""; // title 획득
        $content = isset($_POST["content"]) ? trim($_POST["content"]) : ""; // content 획득

        // 파라미터 에러 체크
        $arr_err_param = [];
        if($title === "") {
            $arr_err_param[] = "title";
        }
        if($content === "") {
            $arr_err_param[] = "content";
        }
        if(count($arr_err_param) > 0) {
            //예외 처리
            throw new Exception("parameter Error : ".implode(", ", $arr_err_param));
        }

        // DB Connect
        $conn = my_db_conn(); // PDO 인스턴스
        
        // Tramsaction 시작
        $conn->beginTransaction();

        // 게시글 작성 처리
        $arr_param = [
            "title" => $title
            ,"content" => $content
        ];
        $result = db_insert_boards($conn, $arr_param);

        // 글 작성 예외처리
        if($result !== 1) {
            throw new Exception("Insert Boards count");
        }

        // 커밋
        $conn->commit();

        // 리스트 페이지로 이동
        header("Location: list.php");
        exit;
    } catch (\Throwable $e) {
        if(!empty($conn) && $conn->inTransaction()) {
            $conn->rollBack();
        }
        echo $e->getMessage();
        exit;
    }finally {
        if(!empty($conn)) {
            $conn = null;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/insert.css">
    <title>main</title>
</head>
<body>
    <a href="./main01.php"><div class="header">PIXEL FOREST</div></a>
    <div class="main">
        <div class="main_top">
            <!-- 오늘 날짜 -->
            <div class="top_date">NOW DATE :<?php echo $current_date ?></div>
            <div class="minus">-</div>
            <div class="square">ㅁ</div>
            <div class="back"><a href="./list.php?date=<?php echo $date?>">x</a></div>
        </div>
        <div class="main_mid">
            <div class="main_left">
                <form action="./insert_ms.php" method="post">
                <div class="main_left_button">
                    <button type="submit">Done</button>
                    <div class="main_left_button01"><a href="./list.php">Cancel</a></div>
                </div>
                <div class="main_left_item">
                    <input type="text" name="title" id="title" spellcheck="false">
                    <textarea name="content" id="content" cols="40" rows="13" spellcheck="false"></textarea>
                </div>
                </form>
            </div>
            <div class="main_right">
                <img src="../image/personal.png" alt="" class="img_p">
                <!-- 리스트 날짜 -->
                <div class="nick_date_item">
                <form action="" method="post">
                    <div class="nick_name_item">
                        <div class="date_controll">
                            <a href="list.php?date=<?php echo $previous_date; ?>"><</a>
                        </div>
                        <div class="nick_name_item-font"><?php echo $date ?></div>
                        <div class="date_controll">
                        <a href="list.php?date=<?php echo $next_date; ?>">></a>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>