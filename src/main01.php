<?php

require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(FILE_LIB_DB); // DB관련 라이브러리

try {
    //PDO 객체 생성
    $conn = my_db_conn();

    //에러 설정
    // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); TODO : 삭제여부 가리기


    //서버date에서 month값을 가져오는 변수
    // $current_month = date('m'); // TODO : 삭제여부 가리기

    // ~~~~~~~~~~~~~~~~~~~ 게이지 변수 모음 ~~~~~~~~~~~~~~~~~~~~~~~

    // 사용자가 달성한 목표 수와 전체 목표 수를 가져옴

    // 오늘 목표 수 가져오기
    $today_goals_count = get_today_goals_count($conn);
    // 달성한 오늘 목표수 가져오기
    $achieved_today_count = get_today_achieved_count($conn);

    // 달성한 이번주 목표 수 가져오기 
    $achieved_week_count = get_week_achieved_count($conn);
    // 주간 전체 목표 수 가져오기
    $week_goals_count = get_week_goals_count($conn);

    // 이번달 목표 수 가져오기
    $month_goals_count = get_month_goals_count($conn);
    // 달성한 이번달 목표 수 가져오기
    $achieved_month_count = get_month_achieved_count($conn);


    // 오늘의 목표 달성률 계산
    if ($today_goals_count > 0) {
        $achievement_rate = ($achieved_today_count / $today_goals_count) * 100; // 오늘 목표 달성률 = (달성한 오늘목표 / 오늘 할 목표 ) * 100
    } else {
        $achievement_rate = 0;
    }
    $achievement_rate = intval($achievement_rate); // 주어진 값을 정수로 바꿔주는 함수 intval() // 백분율로 계산한 값을 정수로 변환

    // 이번 주 목표 달성률 계산
    if ($week_goals_count > 0) {
        $week_achievement_rate = ($achieved_week_count / $week_goals_count) * 100; // 이번주 목표 달성률 = (달성한 이번주 목표 / 이번주 전체 목표수 ) * 100
    } else {
        $week_achievement_rate = 0;
    }
    $week_achievement_rate = intval($week_achievement_rate); // 백분율로 계산한 값을 정수로 변환

    // 이번달 목표 달성률 계산
    if ($month_goals_count > 0) {
        $month_achievement_rate = ($achieved_month_count / $month_goals_count) * 100; // 이번달 목표 달성률 = (달성한 이번달 목표 / 이번달 전체 목표수) * 100
    } else {
        $month_achievement_rate = 0;
    }
    $month_achievement_rate = intval($month_achievement_rate); // 백분율로 계산한 값을 정술 변환

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~



    // ************* 달력 만들기 위한 변수 ****************

    // GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

    // GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
    $month = isset($_GET['month']) ? $_GET['month'] : date('m');

    $date = "$year-$month-01"; // 해당 달의 1일
    $time = strtotime($date); // 현재 날짜의 타임스탬프
    $start_week = date('w', $time); // 1. 시작 요일
    $total_day = date('t', $time); // 2. 현재 달의 총 날짜
    $total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차

    // 이전 월 계산
    $prev_month = date('m', strtotime('-1 month', $time));
    $prev_year = date('Y', strtotime('-1 month', $time));

    // 다음 월 계산 
    $next_month = date('m', strtotime('+1 month', $time));
    $next_year = date('Y', strtotime('+1 month', $time));

    //현재날짜 가져오기
    $current_date = date('Y-m-d');

    // ****************************************************


    // DB 내 아바타이미지 패스 변수에 담기
    $arr_param = [];
    $result = db_select_name_img($conn);
    $img = $result[0]["avatar"];

    // DB 내 닉네임 변수에 담기
    $array_param = [];
    $result_name = db_select_name_img($conn);
    $user_name_get = $result_name[0]["user_name"];

} catch (\Throwable $e) {
    echo "Connection failed: " . $e->getMessage(); // DB커넥트 에러
    exit;
} finally {
    //PDO 파기
    $conn = null;
}

// <!-- 
//     v. 1.0.1
//     작성일자 : 2024-04-04 오전 11시
//     작성(수정)자 : 이나라
//     작성(수정)내용 : div클래스명 규칙

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
//     12. 아바타부분 : avater
// -->
?>




<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main01.css">
    <link rel="shortcut icon" href="./image/favicon.ico" type="image/x-icon">
    <link rel="icon" href="./image/favicon.ico" type="image/x-icon">
    <title>Main</title>
    <style>
        .gauge_bar {
        background-color: #f2ede7;
        margin-top: 5px;
        margin-right: 10px;
        height: 30px;
        }
        .today_gauge_bar_ing {
            width: <?php echo $achievement_rate; ?>%;
            height: 100%;
            background-color : #BDAA8A;
        }
        .week_gauge_bar_ing {
            width: <?php echo $week_achievement_rate; ?>%;
            height: 100%;
            background-color : #BDAA8A;
        }
        .month_gauge_bar_ing{
            width: <?php echo $month_achievement_rate; ?>%;
            height: 100%;
            background-color : #BDAA8A;
        }
    </style>
</head>
<body>
    <a href="./main01.php"><div class="header">PIXEL FOREST</div></a>
    <div class="main">
        <div class="main_top"> 
            <div class="top_date">NOW DATE :<?php echo $current_date ?></div>
            <div class="minus">-</div>
            <div class="square">ㅁ</div>
            <div class="back">
                <label for="toggle"><img src="../image/Gear.png" alt="" class="Gear"></label>
                <input type="checkbox" id="toggle"></input>
                <div class="dropdown">
                    <form action="select_img.php" method="post">
                        <div>
                            <label for="music" class="drop_titles">MUSIC</label>
                        </div>
                        <input type="range" name="" id="music">
                        <div class="drop_titles">Character</div>
                        <div class="character_main">
                            <input type="radio" name="avatar" id="image1" value="/image/avatar01.png">
                            <label for="image1" class="radio_label sum01"></label>

                            <input type="radio" name="avatar" id="image2" value="/image/avatar02.png">                            
                            <label for="image2" class="radio_label sum02"></label>

                            <input type="radio" name="avatar" id="image3" value="/image/avatar03.png">
                            <label for="image3" class="radio_label sum03"></label>

                            <input type="radio" name="avatar" id="image4" value="/image/avatar04.png">
                            <label for="image4" class="radio_label sum04"></label>
                        </div>
                        <button type="submit" class="name_button">YES</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="main_mid">
            <div class="main_left">
                <div class="gauge_item">
                    <div class="gauge_name">Day</div>
                    <div class="gauge_bar">
                        <div class="today_gauge_bar_ing"></div>
                    </div>
                </div>
                <div class="gauge_item">
                    <div class="gauge_name">Week</div>
                    <div class="gauge_bar">
                        <div class="week_gauge_bar_ing"></div>
                    </div>
                </div>
                <div class="gauge_item">
                    <div class="gauge_name">Month</div>
                    <div class="gauge_bar">
                        <div class="month_gauge_bar_ing"></div>
                    </div>
                </div>
                <div class="cal_item">
                <table>
                    <caption>
                    <?php echo '<a class="month" href="?year=' . $prev_year . '&month=' . $prev_month . '">&lt; </a>'; ?>
                        <div class="month_block">
                            <?php echo date('Y F', strtotime($date)); ?> 
                        </div>
                    <?php echo '<a class="month" href="?year=' . $next_year . '&month=' . $next_month . '"> &gt;</a>'; ?>
                    </caption>
                        <thead>
                            <tr>
                                <th class="sun"><p class="sun">S</p></th>
                                <th><p>M</p></th>
                                <th><p>T</p></th>
                                <th><p>W</p></th>
                                <th><p>T</p></th>
                                <th><p>F</p></th>
                                <th class="sat"><p class="sat">S</p></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $day = 1; // 표시할 날짜 초기화
                                for ($i = 0; $i < $total_week; $i++) { // 주(행) 반복
                                    echo '<tr>'; // 새로운 행 시작
                                    for ($j = 0; $j < 7; $j++) { // 요일(열) 반복
                                        $cell_date = date("Y-m-d", strtotime($date." + ".($day - 1)." days"));
                                        if (($i == 0 && $j < $start_week) || ($day > $total_day)) {
                                            // 첫 주에서 시작 요일 이전의 빈 칸이거나 현재 달의 날짜를 넘어갔을 때 빈 칸 표시
                                            echo '<td></td>';
                                        } else {
                                            // 유효한 날짜인 경우 해당 날짜로 링크된 셀 표시
                                            if ($cell_date == $current_date) {
                                                // 현재 날짜와 일치하는 경우 배경색 변경
                                                echo '<td><a href="list.php?date='.$cell_date.'" class="today_a">'.$day.'</a></td>';
                                            } else {
                                                // 일치하지 않는 경우 일반적으로 표시
                                                echo '<td><a href="list.php?date='.$cell_date.'">'.$day.'</a></td>';
                                            }
                                            $day++; // 다음 날짜로 이동
                                        }
                                    }
                                    echo '</tr>'; // 행 마무리
                                }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="main_right">
                <form action="user_name.php" method="post">
                    <div class="nick_name_item">
                        <label for="nick" class="name">NAME</label>
                        <input type="text" name="user_name" id="nick" value="<?php echo $user_name_get ?>">
                        <button type="submit" class="name_button">YES</button>
                    </div>
                </form>
                <div class="img_p">
                    <img src="<?php echo $img ?>" alt="">
                </div>
            </div>
        </div>
    </div>
</body>
</html>