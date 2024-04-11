<?php

require_once( $_SERVER["DOCUMENT_ROOT"]."/config_nr.php");

// *********************** f u n c t i o n **************************

    // gh - 목표 달성 처리 함수
    function achieve_goal($goal_id, $conn) {
        $sql = "UPDATE boards SET checked_at = curdate() WHERE no = :no ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(':id', $goal_id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    }

    // gh - 사용자가 달성한  오늘 목표의 수를 가져오는 함수
    function get_today_achieved_count($conn) {
        $sql = "SELECT COUNT(*) AS count FROM boards WHERE checked_at is not null and DATE(target_date) = CURDATE() ";
        $stmt = $conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row["count"];
    }

    // gh - 오늘 목표 수를 가져오는 함수
    function get_today_goals_count($conn) {
        $sql = "SELECT COUNT(*) AS count FROM boards where date(target_date) = CURDATE() ";
        $stmt = $conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row["count"];
    }
    //이번 주 달성한 목표를 가져오는 함수
    function get_week_achieved_count($conn) {
        try {
            // 현재 주간의 시작 (일요일)
            $startOfWeek = date('Y-m-d', strtotime('last sunday'));
            // 현재 주간의 끝 (토요일)
            $endOfWeek = date('Y-m-d', strtotime('next saturday'));
    
            $sql = "SELECT COUNT(*) AS count FROM boards WHERE checked_at IS NOT NULL AND target_date BETWEEN :startOfWeek AND :endOfWeek";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':startOfWeek', $startOfWeek);
            $stmt->bindValue(':endOfWeek', $endOfWeek);
            $stmt->execute();
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row["count"];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0; // 에러 발생 시 0을 반환하거나 적절한 처리를 수행
        }
    }

    // 주간 전체 목표 수를 가져오는 함수
    function get_week_goals_count($conn) {
    try {
        // 현재 주간의 시작 (일요일)
        $startOfWeek = date('Y-m-d', strtotime('last sunday'));
        // 현재 주간의 끝 (토요일)
        $endOfWeek = date('Y-m-d', strtotime('next saturday'));

        $sql = "SELECT COUNT(*) AS count FROM boards WHERE target_date BETWEEN :startOfWeek AND :endOfWeek";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':startOfWeek', $startOfWeek);
        $stmt->bindValue(':endOfWeek', $endOfWeek);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row["count"];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0; // 에러 발생 시 0을 반환하거나 적절한 처리를 수행
    }
    }

    //서버date에서 month값을 가져오는 변수
    $currentMonth = date('m');
    //이번달 달성한 목표 수를 가져오는 함수
    function get_month_achieved_count($conn) {
        try {
            $currentMonth = date('m'); // 현재 월 가져오기
    
            $sql = "SELECT COUNT(*) AS count FROM boards WHERE checked_at IS NOT NULL AND MONTH(target_date) = :currentMonth";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':currentMonth', $currentMonth, PDO::PARAM_INT);
            $stmt->execute();
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row["count"];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0; // 에러 발생 시 0을 반환하거나 적절한 처리를 수행
        }
    }
    
    // 이번 달 전체 목표 수를 가져오는 함수
    function get_month_goals_count($conn) {
        try {
            $currentMonth = date('m'); // 현재 월 가져오기
    
            $sql = "SELECT COUNT(*) AS count FROM boards WHERE MONTH(target_date) = :currentMonth";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':currentMonth', $currentMonth, PDO::PARAM_INT);
            $stmt->execute();
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row["count"];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0; // 에러 발생 시 0을 반환하거나 적절한 처리를 수행
        }
    }

    //gh - 달력 만드는 함수
    function generateCalendar() {
        $today = date("Y-m-d");
        $firstDayOfMonth = date("Y-m-01");
        $lastDayOfMonth = date("Y-m-t");
        $startDayOfWeek = date("N", strtotime($firstDayOfMonth));
        $endDayOfWeek = date("N", strtotime($lastDayOfMonth));
        $totalDays = date("t", strtotime($firstDayOfMonth));
    
        for ($i = 1; $i < $startDayOfWeek; $i++) {
            echo "<th class='calendar-date'></th>";
        }
        
        for ($day = 1; $day <= $totalDays; $day++) {
            if ($day == date("j", strtotime($today))) {
                echo "<th class='calendar-date today'><a href='list_gh.php?date=" . date("Y-m-d", strtotime($firstDayOfMonth . "+" . ($day - 1) . " days")) . "'>$day</a></th>";
            } else {
                echo "<th class='calendar-date'><a href='list_gh.php?date=" . date("Y-m-d", strtotime($firstDayOfMonth . "+" . ($day - 1) . " days")) . "'>$day</a></th>";
            }
            
            if($day == $endDayOfWeek){
                echo "\n";
            }
        }
        
        for ($i = $endDayOfWeek; $i < 7; $i++) {
            echo "<th class='calendar-date'></th>";
        }
    
    }

    // nr - 이미지 가져오는 함수
    function db_select_img(&$conn, $arr_param) {
        //sql
        $sql = " SELECT img FROM select_img WHERE id = 1 ";
    
        $stmt = $conn->prepare($sql);
        $stmt->execute($arr_param);
        $result = $stmt->fetchAll();
        return $result;
    }
    
    // nr - 이미지 업데이트 함수
    function db_update_image(&$conn, &$arr_param){
        //sql
        $sql = " UPDATE select_img SET img = :img WHERE id = 1 ";
    
        //query start
        $stmt = $conn->prepare($sql);
        $stmt->execute($arr_param);
    
        //return
        return $stmt->rowCount();
    }
    
    // nr - 닉네임 가져오는 함수
    function db_select_user_name(&$conn){
        //sql
        $sql = " SELECT user_name FROM select_img WHERE id = 1 ";
    
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
    
    // nr - 닉네임 업데이트 함수
    function db_update_user_name(&$conn, &$array_param){
        //sql
        $sql = " UPDATE select_img SET user_name = :user_name WHERE id = 1 ";
    
        $stmt = $conn->prepare($sql);
        $stmt->execute($array_param);
    
        //return
        return $stmt->rowCount();
    }

// **************************** f i n ********************************

try {
    //PDO 객체 생성
    $conn = new PDO(MARIADB_DSN, MARIADB_USER, MARIADB_PASSWORD);

    //에러 설정
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ------- gh - GET 변수 모음 --------

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
    $achievement_rate = ($achieved_today_count / $today_goals_count) * 100;
} else {
    $achievement_rate = 0;
}
$achievement_rate = intval($achievement_rate);

// 이번 주 목표 달성률 계산
if ($week_goals_count > 0) {
    $week_achievement_rate = ($achieved_week_count / $week_goals_count) * 100;
} else {
    $week_achievement_rate = 0;
}
$week_achievement_rate = intval($week_achievement_rate);

// 이번달 목표 달성률 계산
if ($month_goals_count > 0) {
    $month_achievement_rate = ($achieved_month_count / $month_goals_count) * 100;
} else {
    $month_achievement_rate = 0;
}
$month_achievement_rate = intval($month_achievement_rate);




// GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
// GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
$month = isset($_GET['month']) ? $_GET['month'] : date('m');

$date = "$year-$month-01"; // 해당 달의 1일
$time = strtotime($date); // 현재 날짜의 타임스탬프
$start_week = date('w', $time); // 1. 시작 요일
$total_day = date('t', $time); // 2. 현재 달의 총 날짜
$total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차

// 이전 월과 다음 월 계산
$prevMonth = date('m', strtotime('-1 month', $time));
$prevYear = date('Y', strtotime('-1 month', $time));
$nextMonth = date('m', strtotime('+1 month', $time));
$nextYear = date('Y', strtotime('+1 month', $time));
//현재날짜 가져오기
$current_date = date('Y-m-d');

// ---------------------------------------------------------------
// nr - get변수 모음 ---------------------------------------------
    $arr_param = [];
    $result = db_select_img($conn, $arr_param);
    $img = $result[0]["img"];

    $array_param = [];
    $result_name = db_select_user_name($conn);
    $user_name_get = $result_name[0]["user_name"];
// ---------------------------------------------------------------

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
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
//     12. 아바타부분 : personal_img
// -->
?>




<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main01.css">
    <title>main</title>
    <style>
        .gauge_bar {
        background-color: #f2ede7;
        /* display: flex; */
        margin-top: 5px;
        margin-right: 10px;
        height: 30px;
        /* overflow: hidden; */
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
                            <input type="radio" name="img" id="image1" value="/image/avatar01.png">
                            <label for="image1" class="radio_label"></label>

                            <input type="radio" name="img" id="image2" value="/image/avatar02.png">                            
                            <label for="image2" class="radio_label"></label>

                            <input type="radio" name="img" id="image3" value="/image/avatar03.png">
                            <label for="image3" class="radio_label"></label>

                            <input type="radio" name="img" id="image4" value="/image/avatar04.png">
                            <label for="image4" class="radio_label"></label>
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
                    <?php echo '<a class="month" href="?year=' . $prevYear . '&month=' . $prevMonth . '">&lt; </a>'; ?>
                        <div class="month_block">
                            <?php echo date('Y F', strtotime($date)); ?>
                        </div>
                    <?php echo '<a class="month" href="?year=' . $nextYear . '&month=' . $nextMonth . '"> &gt;</a>'; ?>
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