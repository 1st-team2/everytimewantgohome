<?php
// <!-- 
//     v. 1.0.1
//     작성일자 : 2024-04-04 오전 11시
//     작성(수정)자 : 이나라
//     작성(수정)내용 : div클래스명 규칙
//     v. 1.0.2
//     작성일자 : 2024-04-05 오후 2시
//     작성(수정)자 : 노경호
//     작성(수정)내용 : list페이지 명명규칙

//     클래스명
//     1. 'TODO LIST' : header
//     2. 창 부분 : main
//     3. 창 상단 최소화,전체화면,뒤로가기(이미지수정)부분 : main_top
//     3-1. 날짜 들어갈곳 : top_date
//     3-2. 최소화(-) : minus
//     3-3. 네모(ㅁ) : square
//     3-4. 뒤로가기(x) : back 
//     4. 창 화면 부분 : main_mid
//     5. 게이지,달력 부분 : main_left
//     6. 이름,프사 부분 : main_right
//     7. 게이지 이름 : gauge_name
//     8. 게이지 부분 : gauge_bar
//     9. 달력 이름 : cal_name
//     10. 달력 : cal
//     11. 닉네임 부분 : nick_name
//     12. 프사부분 : personal_img
//  -->
require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
require_once(FILE_LIB_DB); // DB관련 라이브러리
$list_cnt = 100; // 한 페이지 최대 표시 수
$page_num = 1; // 페이지 번호 초기화

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
    ];
    $result = db_select_boards_paging($conn, $arr_param);

    // 아이템 셋팅
    $item = $result[0];

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
    <link rel="stylesheet" href="./css/list_ms.css">
    <title>TODO LIST</title>
</head>
<body>
    <a href="./main01.php"><div class="header">TODO LIST</div></a>
    <div class="main">
        <div class="main_top"> <!--이미지로 대체-->
            <div class="top_date">2024-04-04</div>
            <div class="minus">-</div>
            <div class="square">ㅁ</div>
            <div class="back"><a href="./main01.php">x</a></div>
        </div>
        <div class="main_mid">
            <div class="main_left">
                <div class="main_list_button">
                    <div class="add_button_box">
                        <a class="add_button" href="./insert.php">Add</a>
                    </div>
                </div>
                <div class="list_container" >
                    <div class="list_items" >
                        <?php
                            foreach($result as $item) {
                        ?>
                            <form action="./chk_ms.php" method="post">
                                <div class="daily_list">
                                    <label class="chk_label <?php echo isset($item["checked_at"]) ? "chk-label-checked" : "" ?>" for="chk_label<?php echo $item["no"];?>"><?php echo isset($item["checked_at"]) ? "✔" : "" ?></label>
                                    <button type="submit" id="chk_label<?php echo $item["no"];?>">a</button>
                                    <div class="itme-button-a"><a href="./update.php?no=<?php echo $item["no"]?>" class="<?php echo isset($item["checked_at"]) ? "color" : "" ?>"><?php echo $item["title"] ?></a></div>
                                </div>
                            <input type="hidden" name="no" value="<?php echo $item["no"]; ?>">
                        </form>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="main_right">
                <form action="" method="post" enctype="multipart/form-data" class="img_add" >
                    <div class="personal_img">
                        <input type="file" name="personal_img" id="personal_img">
                    </div>
                    <button type="submit">OK</button>
                </form>
                <img src="../image/personal.png" alt="" class="img_p">
                <form action="./lis_mst/php" method="post">
                    <div class="nick_name_item">
                        <div class="nick_date_item"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>