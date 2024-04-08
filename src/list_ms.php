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

try {
    $conn = my_db_conn();
    $content_no = isset($_POST["no"]) ? $_POST["no"] : "";
    $page = isset($_POST["page"]) ? $_POST["page"] : "";
    $arr_param = [
        "no" => $no
    ];
    $conn->beginTransaction();
    $result = db_update_contents_checked_at($conn, $arr_param);
    $conn->commit();
    // 상세 페이지로 이동
    header("Location: list.php?page=".$page);
} catch(\Throwable $e) {
    echo $e->getMessage();
    exit;
} finally {
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
                    <form action="./list.html" method="post">
                        <div class="list_items" >
                            <div class="daily_list">
                                <!-- <div class="list_chkbox"><input type="checkbox" name="" id="checkbox"></div> -->
                                <label class="checkbox chk-label-checked <?php echo isset($item["checked_at"]) ? "chk-label-checked" : "" ?>" for="checkbox<?php echo $item["no"];?>"><?php echo isset($item["checked_at"]) ? "✔" : "" ?></label>
                                <div class="list_title"><a href="./detail.php?no=<?php echo $item["no"] ?>&page=<?php echo $page_num ?>" class="<?php echo isset($item["checked_at"]) ? "color" : "" ?>"><?php echo $item["title"] ?></a></div>
                                <!-- <div class="list_title"><a href="./detail.html"><?php echo $item["title"] ?></a></div> -->
                            </div>
                        </div>
                    </form>
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
                <form action="./list/php" method="post">
                    <div class="nick_name_item">
                        <div class="nick_date_item"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>