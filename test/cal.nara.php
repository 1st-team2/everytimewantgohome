<?php

function make_cal($param_year, $param_month, $param_day){

    $temp_arr_31 = [1, 3, 5, 7, 8, 10, 12];
    $temp_arr_30 = [2, 4, 6, 9, 11];

    if( in_array($param_month, $temp_arr_31)){
        $ii = 31;
    } else if( in_array($param_month, $temp_arr_30)){
        $ii = 30;
    } else {
        if($param_year % 4 === 0){
            $ii = 29;
        } else {
            $ii = 28;
        }
    }

    for($i=1; $i <= $param_day; $i++){
        echo "<p></p>";
    }

    for($i=1; $i <= $ii; $i++){
        if($i < 10){
            if($param_month < 10){
            echo "<a href='main01_nr.php?list_date=".$param_year."-0".$param_month."-0".$i."'>".$i."</a>";
            }else {
            echo "<a href='main01_nr.php?list_date=".$param_year."-".$param_month."-0".$i."'>".$i."</a>";
            }
        }
    }

}

