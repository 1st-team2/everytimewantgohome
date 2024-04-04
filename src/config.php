<?php
//path 관련해서 미완성입니다. 240404기준 0.0.1ver




//■ MariaDB 관련	
define("MARIADB_HOST", "112.222.157.156");    //DB HOST
define("MARIADB_USER", "team2");         //DB 유저
define("MARIADB_PASSWORD", "team2");   //DB 비밀번호
define("MARIADB_NAME", "just_do_it");   //DB 명
define("MARIADB_CHARSET", "utf8mb4");   //DB 유니코드
define("MARIADB_DSN", "mysql:host=".MARIADB_HOST.";dbname=".MARIADB_NAME.";charset=".MARIADB_CHARSET);
//상수를 사용하려면 이전에 정의해놔야만 한다. DSN을 나중에 적을 수 있도록 한다.


//■ PHP Path 관련	
define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/var/www/html"); // 웹서버 root 패스  //todo
define("FILE_LIB_DB", ROOT."lib/lib_db.php"); // DB 파일 패스                   //todo

//유저요청 정보
define("REQUEST_METHOD",strtoupper($_SERVER["REQUEST_METHOD"]));



?>