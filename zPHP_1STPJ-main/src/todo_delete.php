<?php
    define( "SRC_ROOT", $_SERVER["DOCUMENT_ROOT"]."/PHP_1STPJ-main/src/" );
    define( "URL_DB", SRC_ROOT."common/db_connect.php" );
    include_once( URL_DB );

    $http_method = $_SERVER["REQUEST_METHOD"];
    
    $arr_get = $_GET;

    if($http_method === "POST")
    {
        $result_cnt = delete_todo_info( $arr_get["list_no"] );
        header( "Location: todo_index.php" );
        exit;
    }
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List : 삭제</title>
    <link rel="stylesheet" href="css/todo_delete_c.css">
    <link rel="icon" href="common/img/magic-book.png">
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="todo_index.php"><img src="common/img/title.png" alt="title"></a>
        </div>
        <div class="delete_outside">
            <div class="delete_container">
                <p>퀘스트를 포기 하시겠습니까?
            <br>
            <br>
                주의! 포기한 퀘스트는 사라집니다</p>
            </div>
            <div class="submit_button">
                <form method="post" action="todo_delete.php?list_no=<?php echo $arr_get["list_no"]."&list_start_date=".$arr_get["list_start_date"] ?>">
                    <a href="todo_detail.php?list_no=<?php echo $arr_get["list_no"]."&list_start_date=".$arr_get["list_start_date"] ?>"><button type="button">취소</button></a>
                    <button type="submit">포기</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

