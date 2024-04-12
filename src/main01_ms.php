<?php

require_once( $_SERVER["DOCUMENT_ROOT"]."/config_nr.php");

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

function db_select_img(&$conn, $arr_param) {
    //sql
    $sql = " SELECT img FROM select_img WHERE id = 1 ";

    $stmt = $conn->prepare($sql);
    $stmt->execute($arr_param);
    $result = $stmt->fetchAll();
    return $result;
}

function db_update_image(&$conn, &$arr_param){
    //sql
    $sql = " UPDATE select_img SET img = :img WHERE id = 1 ";

    //query start
    $stmt = $conn->prepare($sql);
    $stmt->execute($arr_param);

    //return
    return $stmt->rowCount();
}

function db_select_user_name(&$conn){
    //sql
    $sql = " SELECT user_name FROM select_img WHERE id = 1 ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}

function db_update_user_name(&$conn, &$array_param){
    //sql
    $sql = " UPDATE select_img SET user_name = :user_name WHERE id = 1 ";

    $stmt = $conn->prepare($sql);
    $stmt->execute($array_param);

    //return
    return $stmt->rowCount();
}

try {
    // 데이터베이스 연결
    $conn = new PDO(MARIADB_DSN, MARIADB_USER, MARIADB_PASSWORD);

    // if(REQUEST_METHOD == "POST") {
    //     // var_dump($_POST["img"]);
    //     $img = isset($_POST["img"]) ? trim($_POST["img"]) : "/image/personal.png";          
    //     //Transaction 시작
    //     $conn->beginTransaction();

    //     //이미지패스 수정
    //     $arr_param = [
    //         "img" => $img
    //     ];

    //     $result = db_update_image($conn, $arr_param);

    //     //예외처리
    //     // if($result !== 1){
    //     //     throw new Exception("Update Boards no count");
    //     // }
    //     //commit
    //     $conn->commit();

    //     $item = $result;
    // }

    if(REQUEST_METHOD == "POST"){

        // 파라미터 가져오기
        $user_name = isset($_POST["user_name"]) ? $_POST["user_name"] : "적용안됨";

        //Transaction 시작
        $conn->beginTransaction();

        //이름 수정
        $arr_param = [
            "user_name" => $user_name
        ];
        
        $result = db_update_user_name($conn, $arr_param);

        $conn->commit();

    }

    $arr_param = [];
    $result = db_select_img($conn, $arr_param);
    $img = $result[0]["img"];

    $array_param = [];
    $result_name = db_select_user_name($conn);
    $user_name_get = $result_name[0]["user_name"];

} catch (PDOException $e) {
    // if(!empty($conn) && $conn->inTransaction()){
    //     $conn->rollBack();
    // }
    echo $e->getMessage();
    exit;
} finally {
    if(!empty($conn)){
        $conn = null;
    }
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
//     12. 프사부분 : personal_img
// -->

?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main01.css">
    <!-- <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"> -->
    <link rel="shortcut icon" href="./image/favicon.ico" type="image/x-icon">
    <link rel="icon" href="./image/favicon.ico" type="image/x-icon">
    <title>main</title>
</head>
<body>
    <a href="./main01_ms.php"><div class="header">PIXEL FOREST</div></a>
    <div class="main">
        <div class="main_top">
            <div class="top_date"></div>
            <div class="minus">-</div>
            <div class="square">ㅁ</div>
            <div class="back">
                <label for="toggle"><img src="./image/Gear.png" alt="" class="Gear"></label>
                <input type="checkbox" id="toggle"></input>
                <div class="dropdown">
                    <form action="select_img.php" method="post">
                        <div>
                            <label for="music" class="drop_titles">MUSIC</label>
                        </div>
                        <input type="range" name="" id="music">
                        <div class="drop_titles">Character</div>
                        <div class="character_main">
                            <input type="radio" name="img" id="image1" value="/image/jing.png">
                            <label for="image1" class="radio_label">
                                <!-- <img src="/image/personal.png"> -->
                            </label>

                            <input type="radio" name="img" id="image2" value="/image/ex.jpg">                            
                            <label for="image2" class="radio_label">
                                <!-- <img src="/image/personal.png"> -->
                            </label>

                            <input type="radio" name="img" id="image3" value="/image/cat.png">
                            <label for="image3" class="radio_label">
                                <!-- <img src="/image/personal.png"> -->
                            </label>

                            <input type="radio" name="img" id="image4" value="/image/personal.png">
                            <label for="image4" class="radio_label">
                                <!-- <img src="/image/personal.png"> -->
                            </label>
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
                            <!-- PHP로 동적으로 날짜 채우기 -->
                            <?php
                            // 현재 년월 계산
                            $currentYear = date('Y');
                            $currentMonth = date('n');
                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
                            $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
                            $dayOfWeek = date('w', $firstDayOfMonth);

                            $currentDate = 1;

        // 달력 표시
        for ($i = 0; $i < 6; $i++) { // 최대 6주 (최대 행 수)
            echo '<tr>';
            for ($j = 0; $j < 7; $j++) { // 일주일은 7일
                if (($i == 0 && $j < $dayOfWeek) || $currentDate > $daysInMonth) {
                    echo '<td></td>'; // 빈 셀
                } else {
                    // 날짜 셀에 날짜 출력
                    echo '<td><a href="list.php?date=' . $currentYear . '-' . $currentMonth . '-' . $currentDate . '">' . $currentDate . '</a></td>';
                    $currentDate++;
                }
            }
            echo '</tr>';
        }
        ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="main_right">
                <form action="main01_ms.php" method="post">
                    <div class="nick_name_item">
                        <label for="nick" class="name">NAME</label>
                        <input type="text" name="nick" id="nick" value="<?php echo $user_name ?>">
                        <button type="submit" class="name_button" >YES</button>
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