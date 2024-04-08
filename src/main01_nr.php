<?php

require_once( $_SERVER["DOCUMENT_ROOT"]."/config.php"); // 설정 파일 호출
//require_once(FILE_LIB_DB); // DB관련 라이브러리

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
    echo "<div class='cal_gpt'>";
    echo "  <div class='calendar-date'>Sun</div>";
    echo "  <div class='calendar-date'>Mon</div>";
    echo "  <div class='calendar-date'>Tue</div>";
    echo "  <div class='calendar-date'>Wed</div>";
    echo "  <div class='calendar-date'>Thu</div>";
    echo "  <div class='calendar-date'>Fri</div>";
    echo "  <div class='calendar-date'>Sat</div>";
    echo "</div>";
    for ($i = 1; $i < $startDayOfWeek; $i++) {
        echo "<div class='calendar-date'></div>";
    }
    
    for ($day = 1; $day <= $totalDays; $day++) {
        if ($day == date("j", strtotime($today))) {
            echo "<div class='calendar-date today'><a href='list.php?date=" . date("Y-m-d", strtotime($firstDayOfMonth . "+" . ($day - 1) . " days")) . "'>$day</a></div>";
        } else {
            echo "<div class='calendar-date'><a href='list.php?date=" . date("Y-m-d", strtotime($firstDayOfMonth . "+" . ($day - 1) . " days")) . "'>$day</a></div>";
        }
    }
    
    for ($i = $endDayOfWeek; $i < 7; $i++) {
        echo "<div class='calendar-date'></div>";
    }
}

generateCalendar();

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
//     12. 프사부분 : personal_img
// -->
?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main01.css">
    <title>main</title>
</head>
<body>
    <div class="header">TODO LIST</div>
    <div class="main">
        <div class="main_top">
            <div class="top_date"></div>
            <div class="minus">-</div>
            <div class="square">ㅁ</div>
            <div class="back">
                <label for="toggle"><img src="./image/Gear.png" alt="" class="Gear"></label>
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
                        <div class="gauge_bar_yet"></div>
                    </div>
                </div>
                <div class="gauge_item">
                    <div class="gauge_name">Week</div>
                    <div class="gauge_bar">
                        <div class="gauge_bar_ing"></div>
                        <div class="gauge_bar_yet"></div>
                    </div>
                </div>
                <div class="gauge_item">
                    <div class="gauge_name">Month</div>
                    <div class="gauge_bar">
                        <div class="gauge_bar_ing"></div>
                        <div class="gauge_bar_yet"></div>
                    </div>
                </div>
                <div class="cal_item">
                    <!-- <div class="cal_name">April</div>
                    <div class="cal">달력</div> -->
                <table>
                    <caption>April</caption>
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
                            <!-- <tr>
                                <td class="otherMonth"></td>
                                <td><a href="./list.html">1</a></td>
                                <td><a href="./list.html">2</a></td>
                                <td><a href="./list.html">3</a></td>
                                <td><a href="./list.html">4</a></td>
                                <td><a href="./list.html">5</a></td>
                                <td class="sat"><a href="./list.html">6</a></td>
                            </tr>
                            <tr>
                                <td class="sun"><a href="./list.html">7</a></td>
                                <td><a href="./list.html">8</a></td>
                                <td><a href="./list.html">9</a></td>
                                <td><a href="./list.html">10</a></td>
                                <td><a href="./list.html">11</a></td>
                                <td><a href="./list.html">12</a></td>
                                <td class="sat"><a href="./list.html">13</a></td>
                            </tr>
                            <tr>
                                <td class="sun"><a href="./list.html">14</a></td>
                                <td><a href="./list.html">15</a></td>
                                <td><a href="./list.html">16</a></td>
                                <td><a href="./list.html">17</a></td>
                                <td><a href="./list.html">18</a></td>
                                <td><a href="./list.html">19</a></td>
                                <td class="sat"><a href="./list.html">20</a></td>
                            </tr>
                            <tr>
                                <td class="sun"><a href="./list.html">21</a></td>
                                <td><a href="./list.html">22</a></td>
                                <td><a href="./list.html">23</a></td>
                                <td><a href="./list.html">24</a></td>
                                <td><a href="./list.html">25</a></td>
                                <td><a href="./list.html">26</a></td>
                                <td class="sat"><a href="./list.html">27</a></td>
                            </tr>
                            <tr>
                                <td class="sun"><a href="./list.html">28</a></td>
                                <td><a href="./list.html">29</a></td>
                                <td><a href="./list.html">30</a></td>
                                <td class="otherMonth"></td>
                                <td class="otherMonth"></td>
                                <td class="otherMonth"></td>
                                <td class="otherMonth"></td>
                            </tr> -->
                            <?php generateCalendar(); ?>
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