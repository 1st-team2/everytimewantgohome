<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(FILE_LIB_DB);

//리스트 날짜 url에서 가져오기
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

    if(REQUEST_METHOD === "GET") {
        $no = isset($_GET["no"]) ? ($_GET["no"]) : "";

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
            throw new Exception("Select Boards no count");
        }
        $item = $result[0];

        $img_result = db_select_name_img($conn);
        $img = $img_result[0]["avatar"];

     // POST로 전달된 데이터를 변수에 할당, 없을 경우 빈 문자열로 초기화
    }else if(REQUEST_METHOD === "POST") {
        $no = isset($_POST["no"]) ? ($_POST["no"]) : "";
        $title = isset($_POST["title"]) ? ($_POST["title"]) : "";
        $content = isset($_POST["content"]) ? ($_POST["content"]) : "";
        
        $arr_err_param = [];
        if($no === "") {
            $arr_err_param[] = "no";
        }
        if($title === "") {
            $arr_err_param[] = "title";
        }
        if($content === "") {
            $arr_err_param[] = "content";
        }
        if(count($arr_err_param) > 0 ) {
            throw new Exception("Parameter Error : ".implode(", ", $arr_err_param));
        }
        //Transaction 시작
        $conn->beginTransaction();
        
        //게시글 수정 처리
        $arr_param = [
            "no" => $no
            ,"title" => $title
            ,"content" => $content
        ];

        $result = db_update_boards_no($conn, $arr_param);
        
        //수정 예외 처리
        if($result !== 1 ) {
            throw new Exception("Update Boards no count");
        }
        //commit
        $conn->commit();

        //상세 페이지로 이동
        header("Location: detail.php?date={$date}&no={$no}");
        // {}를 이용하거나 연결연산자 dot을 이용.
        exit;
    }
} catch (\Throwable $e) {
    if(!empty($conn) && $conn->inTransaction()){
        $conn->rollBack();
    }
    echo $e->getMessage();
    exit;
}finally {
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
    <link rel="stylesheet" href="./css/update.css">
    <link rel="shortcut icon" href="./image/favicon.ico" type="image/x-icon">
    <link rel="icon" href="./image/favicon.ico" type="image/x-icon">
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
            <div class="back"><a href="./detail.php?date=<?php echo $date?>&no=<?php echo $item["no"]; ?>">x</a></div>
        </div>
        <div class="main_mid">
            <div class="main_left">
                <form action="./update.php?date=<?php echo $date; ?>" method="post">
                    <input type="hidden" name="no" value="<?php echo $item["no"]; ?>">
                    <div class="main_left_button">
                        <button type="submit">Done</button>
                        <div class="main_left_button01"><a href="./list.php?date=<?php echo $date?>">Cancel</a></div>
                    </div>
                    <div class="main_left_item">
                        <input type="text" name="title" id="title" spellcheck="false" value="<?php echo $item["title"]; ?>" minlength="1" maxlength="30">
                        <textarea name="content" id="content" cols="40" rows="13" spellcheck="false"><?php echo $item["content"] ?></textarea>
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
</body>
</html>