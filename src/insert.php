<?php
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리.

// nr - 이미지 가져오는 함수
function db_select_img(&$conn) {
    //sql
    $sql = " SELECT img FROM select_img WHERE id = 1 ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}
try {
    //DB connect
    $conn = my_db_conn();  //PDO 인스턴스
    
    if(REQUEST_METHOD === "GET") {
        //리스트 날짜 url에서 가져오기
        $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
        
        //현재날짜 가져오기
        $current_date = date('Y-m-d');
        
        // 이전 날짜 계산 (하루 전)
        $previous_date = date('Y-m-d', strtotime($date . ' -1 day'));
        
        // 다음 날짜 계산 (하루 후)
        $next_date = date('Y-m-d', strtotime($date . ' +1 day'));

        $img_result = db_select_img($conn);
        $img = $img_result[0]["img"];

    } else if(REQUEST_METHOD === "POST") {

        //파라미터 획득
        $title = isset($_POST["title"]) ? trim($_POST["title"]) : "";       // title 획득
        $content = isset($_POST["content"]) ? trim($_POST["content"]) : ""; // contetn 획득
        $date = isset($_POST["date"]) ? $_POST["date"] : "";

        //파라미터 에러 체크
        $arr_err_param = [];
        if($title === "") {
            $arr_err_param[] = "title";
        }
        if($content === "") {
            $arr_err_param[] = "content";
        }
        if($date === ""){
            $arr_err_param[] = "date";
        }
        if(count($arr_err_param) > 0) {
            throw new Exception("parameter Error : ".implode(", ",$arr_err_param));
        }


        //Transaction  
        $conn->beginTransaction();
        //게시글 작성 처리
        $arr_param = [
            "title" => $title
            ,"content" => $content
            ,"target_date"=>$date
        ];
        $result = db_insert_boards($conn, $arr_param);

        //글 작성 예외처리
        if($result !== 1) {
            throw new Exception("Insert Boards count");
        }
        //커밋
        $conn->commit();
        //리스트 페이지로 이동
        header("Location: list.php?date={$date}");
        exit;
        }
    } catch (\Throwable $e) {
        if(!empty($conn) && $conn->inTransaction()) {
            $conn->rollBack();
        }
        echo $e->getMessage();
        exit;
    } finally {
        if(!empty($conn)) {
            $conn = null;
        }
    }

?>


?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/insert.css">
    <title>Insert</title>
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
                <form action="./insert.php" method="post">
                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                <div class="main_left_button">
                    <button type="submit">Done</button>
                    <div class="main_left_button01"><a href="./list.php?date=<?php echo $date; ?>">Cancel</a></div>
                </div>
                <div class="main_left_item">
                    <input type="text" name="title" id="title" spellcheck="false">
                    <textarea name="content" id="content" cols="40" rows="13" spellcheck="false"></textarea>
                </div>
                </form>
            </div>
            <div class="main_right">
                <img src="<?php echo $img ?>" alt=""class="img_p">
                <!-- 리스트 날짜 -->
                <div class="nick_date_item">
                    <div class="nick_name_item-font"><?php echo $date ?></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>