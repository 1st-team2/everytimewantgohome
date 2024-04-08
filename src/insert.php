<!-- 
    v. 1.0.1
    작성일자 : 2024-04-05 오전 11시
    작성(수정)자 : 권민서
    작성(수정)내용 : div클래스명 규칙

    클래스명
    1. 'TODO LIST' : header
    2. 창 부분 : main
    3. 창 상단 최소화,전체화면,뒤로가기(이미지수정)부분 : main_top
    3-1. 날짜 들어갈곳 : top_date
    3-2. 최소화(-) : minus
    3-3. 네모(ㅁ) : square
    3-4. 뒤로가기(x) : back 
    4. 창 화면 부분 : main_mid
    5. 게이지,달력 부분 : main_left
    6. 이름,프사 부분 : main_right
    7. 게이지 이름 : gauge_name
    8. 게이지 부분 : gauge_bar
    9. 달력 이름 : cal_name
    10. 달력 : cal
    11. 닉네임 부분 : nick_name
    12. 프사부분 : personal_img
 -->

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/insert.css">
    <title>main</title>
</head>
<body>
    <a href="./main01.php"><div class="header">TODO LIST</div></a>
    <div class="main">
        <div class="main_top">
            <!-- 오늘 날짜 -->
            <div class="top_date">2024-03-26</div>
            <div class="minus">-</div>
            <div class="square">ㅁ</div>
            <div class="back"><a href="./list.php">x</a></div>
        </div>
        <div class="main_mid">
            <div class="main_left">
                <form action="" method="post">
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
                <div class="nick_date_item">2024-03-26</div>
            </div>
        </div>
    </div>
</body>
</html>