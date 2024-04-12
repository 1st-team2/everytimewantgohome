<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "php505";
$dbname = "toolist";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 목표 달성 처리 함수
function achieveGoal($goalId) {
    global $conn;

    // 목표 달성 상태를 1로 업데이트
    $sql = "UPDATE goals SET achieved = 1 WHERE id = $goalId";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// 사용자가 달성한 목표의 수를 가져오는 함수
function getAchievedGoalsCount() {
    global $conn;

    $sql = "SELECT COUNT(*) AS count FROM goals WHERE achieved = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["count"];
    } else {
        return 0;
    }
}

// 전체 목표 수를 가져오는 함수
function getTotalGoalsCount() {
    global $conn;

    $sql = "SELECT COUNT(*) AS count FROM goals";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["count"];
    } else {
        return 0;
    }
}

// 사용자가 달성한 목표 수와 전체 목표 수를 가져옴
$achievedCount = getAchievedGoalsCount();
$totalCount = getTotalGoalsCount();

// 전체 목표 달성률 계산
if ($totalCount > 0) {
    $progressPercentage = ($achievedCount / $totalCount) * 100;
} else {
    $progressPercentage = 0;
}

$conn->close();
?>