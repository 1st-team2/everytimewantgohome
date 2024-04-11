<?php 
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리
$list_cnt = 100; // 한 페이지 최대 표시 수
$page_num = 1; // 페이지 번호 초기화

//날짜 관련 - last update : 노경호 0409
//0411 오후2시 55분 함수 추가, 이미지변경시작 : 이나라 / 오후 3시40분 완료

//리스트 날짜 url에서 가져오기
// $date = $_GET['date'];
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');


//현재날짜 가져오기
$current_date = date('Y-m-d');

// 이전 날짜 계산 (하루 전)
$previous_date = date('Y-m-d', strtotime($date . ' -1 day'));

// 다음 날짜 계산 (하루 후)
$next_date = date('Y-m-d', strtotime($date . ' +1 day'));

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
    // DB Connect
    $conn = my_db_conn(); // connection 함수

    //파라미터에서 page 획득
    $page_num = isset($_GET["page"]) ? $_GET["page"] : $page_num;
    $no = isset($_POST["no"]) ? $_POST["no"] : "";

    $arr_param = [
        "no" => $no
    ];
    $conn->beginTransaction();
    $result = db_update_contents_checked_at($conn, $arr_param);
    $conn->commit();
    // 상세 페이지로 이동
    // header("Location: detail.php?page=".$page);
    
    // 게시글 수 조회
    $result_board_cnt = db_select_boards_cnt($conn);
    
    // 페이지 관련 설정 셋팅
    $max_page_num = ceil($result_board_cnt / $list_cnt); // 최대 페이지 수
    $offset = $list_cnt * ($page_num -1); // 오프셋

    // 게시글 리스트 조회
    $arr_param = [
        "list_cnt" => $list_cnt
        ,"offset" => $offset
        ,"target_date" => $date
    ];
    $result = db_select_boards_paging($conn, $arr_param);

    // 아이템 셋팅
    $item = $result;

    $img_result = db_select_img($conn);
    $img = $img_result[0]["img"];

} catch(\Throwable $e) {
    echo $e->getMessage();
    exit; //위에 코드가 오류 있을때 밑에 코드 안 보이고 종료 시키고 싶을때 사용
} finally {
    // PDO 파기
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
    <link rel="stylesheet" href="./css/list.css">
    <title>Daily List</title>
</head>
<body>
    <a href="./main01.php"><div class="header">PIXEL FOREST</div></a>
    <div class="main">
        <div class="main_top"> <!--이미지로 대체-->
            <div class="top_date">NOW DATE :<?php echo $current_date ?></div>
            <div class="minus">-</div>
            <div class="square">ㅁ</div>
            <div class="back"><a href="./main01.php">x</a></div>
        </div>
        <div class="main_mid">
            <div class="main_left">
                <form action="./insert.php" method="get">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
                <div class="main_list_button">
                    <div class="add_button_box">
                        <!-- <a class="add_button" href="./insert.php?date="> -->
                            <button type="submit">
                                Add
                            </button>
                    <!-- </a> -->
                    </div>
                </form>
                </div>
                <div class="list_container" >
                    <div class="list_items" >
                    <?php
                    if(!empty($result)) {
                        foreach($result as $item) {
                        
                    ?>
                        <form action="./chk_ms.php" method="post">
                        <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
                            <div class="daily_list">
                                <label class="chk_label <?php echo isset($item["checked_at"]) ? "chk-label-checked" : "" ?>" for="chk_label<?php echo $item["no"];?>"><?php echo isset($item["checked_at"]) ? "✔" : "" ?></label>
                                <button type="submit" id="chk_label<?php echo $item["no"];?>"></button>
                                <div class="item-button-a"><a href="./detail.php?date=<?php echo $date ?>&no=<?php echo $item["no"]?>" class="<?php echo isset($item["checked_at"]) ? "color" : "" ?>"><?php echo $item["title"] ?></a></div>
                            </div>
                        <input type="hidden" name="no" value="<?php echo $item["no"]; ?>">
                    </form>
                    <?php
                        } 
                        }else {
                            echo "게시글이 없습니다.";
                        }
                    ?>
                    </div>
                </div>
            </div>
            <div class="main_right">
                <img src="<?php echo $img ?>" alt=""class="img_p">
                <form action="" method="post">
                    <div class="nick_name_item">
                        <div class="date_controll">
                            <a href="list.php?date=<?php echo $previous_date; ?>"><</a>
                        </div>
                        <div><?php echo $date ?></div>
                        <div class="date_controll">
                        <a href="list.php?date=<?php echo $next_date; ?>">></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>