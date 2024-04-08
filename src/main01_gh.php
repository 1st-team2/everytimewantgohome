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
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main01_gh.css">
    <title>main</title>
    <style>
        .gauge_bar_ing {
            width: <?php echo $progressPercentage; ?>%;
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
                        <div class="gauge_bar_ing"><?php echo $progressPercentage; ?></div>
                        <!-- <div class="gauge_bar_yet"></div> -->
                    </div>
                </div>
                <div class="gauge_item">
                    <div class="gauge_name">Week</div>
                    <div class="gauge_bar">
                        <div class="gauge_bar_ing"><?php echo $progressPercentage; ?></div>
                        <!-- <div class="gauge_bar_yet"></div> -->
                    </div>
                </div>
                <div class="gauge_item">
                    <div class="gauge_name">Month</div>
                    <div class="gauge_bar">
                        <div class="gauge_bar_ing">
                            <?php echo $progressPercentage; ?>
                        </div>
                        <!-- <div class="gauge_bar_yet"></div> -->
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
                            <tr>
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
                            </tr>
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