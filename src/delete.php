<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리
$list_cnt = 100; // 한 페이지 최대 표시 수
$page_num = 1; // 페이지 번호 초기화

//리스트 날짜 url에서 가져오기
// $date = $_GET['date'];
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

//현재날짜 가져오기
$current_date = date('Y-m-d');

// 이전 날짜 계산 (하루 전)
$previous_date = date('Y-m-d', strtotime($date . ' -1 day'));

// 다음 날짜 계산 (하루 후)
$next_date = date('Y-m-d', strtotime($date . ' +1 day'));



try {
    //DB connect
    $conn = my_db_conn();                            

    if(REQUEST_METHOD === "GET"){
        $no = isset($_GET["no"]) ? ($_GET["no"]) : "";   

        //파라미터 에러 체크        
        $arr_err_param = [];
        if($no === "") {
            $arr_err_param[] = "no";
        }
        if(count($arr_err_param) > 0 ) {
            throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
        }
        
        $arr_param = [
            "no" => $no
        ];
        $result = db_select_boards_no($conn, $arr_param);
        if(count($result) !== 1) {
            throw new Exception("Select no count");
        }
        $item = $result[0];
        // 이미지
        $img_result = db_select_name_img($conn);
        $img = $img_result[0]["avatar"];
    }
    else if (REQUEST_METHOD === "POST") {
        $no = isset($_POST["no"]) ? $_POST["no"] : "";

        $arr_err_param = [];
        if($no === "") {
            $arr_err_param[] = "no";
        }
        if(count($arr_err_param) > 0 ) {
            throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
        }
        
        $conn->beginTransaction();

        $arr_param = [
            "no" => $no
        ];
        $result = db_delete_boards_no($conn, $arr_param);

        if($result !== 1) {
            throw new Exception("Delete no count");
        }

        $conn->commit();

        header("Location: list.php?date={$date}");
        exit;
    }
} catch (\Throwable $e) {
    if(!empty($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    echo $e->getMessage();
    exit;
}finally {
    //PDO 파기
    if(!empty($conn)) {
        $conn = null;
    }
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/delete.css">
    <link rel="shortcut icon" href="./image/favicon.ico" type="image/x-icon">
    <link rel="icon" href="./image/favicon.ico" type="image/x-icon">
    <title>Delete</title>
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
                <form action="./delete.php" method="post">
                    <div class="main_left_button">
                        <!-- <button type="submit">Update</button> -->
                        <div class="main_left_button01"><a href="./update.php?date=<?php echo $date?>&no=<?php echo $item["no"]?>">Update</a></div>
                        <div class="main_left_button02"><a href="./list.php?date=<?php echo $date?>">Cancel</a></div>
                        <div class="main_left_button03"><a href="./delete.php?date=<?php echo $date?>&no=<?php echo $item["no"]?>">Delete</a></div>
                    </div>
                    <div class="main_left_item">
                        <input type="text" name="title" id="title" spellcheck="false" readonly value="<?php echo $item["title"] ?>">
                        <textarea name="content" id="content" cols="40" rows="13" spellcheck="false" readonly><?php echo $item["content"] ?></textarea>
                    </div>
                </form>
            </div>
            <div class="main_right">
                <img src="<?php echo $img ?>" alt=""class="img_p">
                <!-- 리스트 날짜 -->
                <div class="nick_date_item">
                    <?php echo $date ?>
                </div>
            </div>
        </div>
    </div>
    <div class="pop_up">
        <div class="pop_up_main">
            <div class="pop_up_top">
                <!-- 오늘 날짜 -->
                <div class="pop_top_date"><?php echo $date ?></div>
                <div class="pop_minus">-</div>
                <div class="pop_square">ㅁ</div>
                <div class="pop_back"><a href="./detail.php?date=<?php echo $date;?>&no=<?php echo $no; ?>">x</a></div>
            </div>
            <div class="pop_up_mid">
                <p>
                    삭제 도와드리겠습니다 
                    <br>
                    아니면 
                    <br>
                    다시 생각해 보시겠습니까?
                </p>
                <form action="./delete.php" method="post">
                    <input type="hidden" name="no" value="<?php echo $no; ?>">
                    <div class="pop_up_button">
                        <button type="submit">
                            YES
                        </button>
                        <a href="./detail.php?date=<?php echo $date;?>&no=<?php echo $no; ?>">NO</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>