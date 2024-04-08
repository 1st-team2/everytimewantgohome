<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "php505";
$dbname = "todolist";

try {
    // PDO 객체 생성 (데이터베이스 연결)
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // 에러 모드 설정 (예외 처리)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 목표 달성 처리 함수
    function achieveGoal($goalId, $conn) {
        $sql = "UPDATE goals SET achieved = 1 WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $goalId, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    }

    // 사용자가 달성한 목표의 수를 가져오는 함수
    function getAchievedGoalsCount($conn) {
        $sql = "SELECT COUNT(*) AS count FROM goals WHERE achieved = 1";
        $stmt = $conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row["count"];
    }

    // 전체 목표 수를 가져오는 함수
    function getTotalGoalsCount($conn) {
        $sql = "SELECT COUNT(*) AS count FROM goals";
        $stmt = $conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row["count"];
    }

    // 사용자가 달성한 목표 수와 전체 목표 수를 가져옴
    $achievedCount = getAchievedGoalsCount($conn);
    $totalCount = getTotalGoalsCount($conn);

    // 전체 목표 달성률 계산
    if ($totalCount > 0) {
        $progressPercentage = ($achievedCount / $totalCount) * 100;
    } else {
        $progressPercentage = 0;
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// GET으로 넘겨 받은 year값이 있다면 넘겨 받은걸 year변수에 적용하고 없다면 현재 년도
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
// GET으로 넘겨 받은 month값이 있다면 넘겨 받은걸 month변수에 적용하고 없다면 현재 월
$month = isset($_GET['month']) ? $_GET['month'] : date('m');

$date = "$year-$month-01"; // 해당 달의 1일
$time = strtotime($date); // 현재 날짜의 타임스탬프
$start_week = date('w', $time); // 1. 시작 요일
$total_day = date('t', $time); // 2. 현재 달의 총 날짜
$total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차


function generateCalendar() {
    $today = date("Y-m-d");
    $firstDayOfMonth = date("Y-m-01");
    $lastDayOfMonth = date("Y-m-t");
    $startDayOfWeek = date("N", strtotime($firstDayOfMonth));
    $endDayOfWeek = date("N", strtotime($lastDayOfMonth));
    $totalDays = date("t", strtotime($firstDayOfMonth));
    
    // echo " <div class='calendar-date'>Sun</div>";
    // echo " <div class='calendar-date'>Mon</div>";
    // echo " <div class='calendar-date'>Tue</div>";
    // echo " <div class='calendar-date'>Wed</div>";
    // echo " <div class='calendar-date'>Thu</div>";
    // echo " <div class='calendar-date'>Fri</div>";
    // echo " <div class='calendar-date'>Sat</div>";

    for ($i = 1; $i < $startDayOfWeek; $i++) {
        echo "<th class='calendar-date'></th>";
    }
    
    for ($day = 1; $day <= $totalDays; $day++) {
        if ($day == date("j", strtotime($today))) {
            echo "<th class='calendar-date today'><a href='list.php?date=" . date("Y-m-d", strtotime($firstDayOfMonth . "+" . ($day - 1) . " days")) . "'>$day</a></th>";
        } else {
            echo "<th class='calendar-date'><a href='list.php?date=" . date("Y-m-d", strtotime($firstDayOfMonth . "+" . ($day - 1) . " days")) . "'>$day</a></th>";
        }
        
        if($day == $endDayOfWeek){
            echo "\n";
        }
    }
    
    for ($i = $endDayOfWeek; $i < 7; $i++) {
        echo "<th class='calendar-date'></th>";
    }

}
// 이전 월과 다음 월 계산
$prevMonth = date('m', strtotime('-1 month', $time));
$prevYear = date('Y', strtotime('-1 month', $time));
$nextMonth = date('m', strtotime('+1 month', $time));
$nextYear = date('Y', strtotime('+1 month', $time));

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main01_gh.css">
    <title>main</title>
    <style>
        .gauge_bar {
        background-color: #f2ede7;
        /* display: flex; */
        margin-top: 5px;
        margin-right: 10px;
        height: 30px;
        overflow: hidden;
        }
        .gauge_bar_ing {
            width: <?php echo $progressPercentage; ?>%;
            height: 100%;
            color : #BDAA8A;
        }
    </style>
</head>
<body>
    <div class="header">TODO LIST</div>
    <div class="main">
        <div class="main_top"> <!--이미지로 대체-->
            <div class="top_date"></div>
            <div class="minus">-</div>
            <div class="square">ㅁ</div>
            <div class="back">
                <label for="toggle"><img src="../image/Gear.png" alt="" class="Gear"></label>
                <input type="checkbox" id="toggle"></input>
                <div class="dropdown">
                    <form action="main01.html" method="post">
                        <div>
                            <label for="music" class="drop_titles">MUSIC</label>
                        </div>
                        <input type="range" name="" id="music">
                        <div class="drop_titles">Character</div>
                        <div class="character_main">
                            <div class="char_img_radio">
                                <input type="radio" name="img" id="char_img1">
                                <label for="char_img1"><img src="../image/ex.jpg" alt=""></label>
                            </div>
                            <div class="char_img_radio">
                                <input type="radio" name="img" id="char_img2">
                                <label for="char_img2"><img src="../image/ex.jpg" alt=""></label>
                            </div>
                            <div class="char_img_radio">
                                <input type="radio" name="img" id="char_img3">
                                <label for="char_img3"><img src="../image/ex.jpg" alt=""></label>
                            </div>
                            <div class="char_img_radio">
                                <input type="radio" name="img" id="char_img4">
                                <label for="char_img4"><img src="../image/ex.jpg" alt=""></label>
                            </div>
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
                        <div class="gauge_bar_ing"></div>
                        <!-- <div class="gauge_bar_yet"></div> -->
                    </div>
                </div>
                <div class="gauge_item">
                    <div class="gauge_name">Week</div>
                    <div class="gauge_bar">
                        <div class="gauge_bar_ing"></div>
                        <!-- <div class="gauge_bar_yet"></div> -->
                    </div>
                </div>
                <div class="gauge_item">
                    <div class="gauge_name">Month</div>
                    <div class="gauge_bar">
                        <div class="gauge_bar_ing"></div>
                        <!-- <div class="gauge_bar_yet"></div> -->
                    </div>
                </div>
                <div class="cal_item">
                    <!-- <div class="cal_name">April</div>
                    <div class="cal">달력</div> -->
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
                                    if (($i == 0 && $j < $start_week) || ($day > $total_day)) {
                                         // 첫 주에서 시작 요일 이전의 빈 칸이거나 현재 달의 날짜를 넘어갔을 때 빈 칸 표시
                                        echo '<td></td>';
                                    } else {
                                         // 유효한 날짜인 경우 해당 날짜로 링크된 셀 표시
                                        echo '<td><a href="list.php?date=' . date("Y-m-d", strtotime($date . " + " . ($day - 1) . " days")) . '">' . $day . '</a></td>';
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
                <form action="main01.html" method="post">
                    <div class="nick_name_item">
                        <label for="nick" class="name">NAME</label>
                        <input type="text" name="nick" id="nick">
                        <button type="submit" class="name_button">YES</button>
                    </div>
                </form>
                <form action="main01.html" method="post" enctype="multipart/form-data" class="img_add" >
                    <div class="personal_img">
                        <input type="file" name="personal_img" id="personal_img">
                    </div>
                    <button type="submit">OK</button>
                </form>
                <img src="../image/personal.png" alt="" class="img_p">
            </div>
        </div>
    </div>
</body>
</html>