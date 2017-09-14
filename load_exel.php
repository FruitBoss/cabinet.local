<?php

    session_start();
    header("Content-Type: text/html; charset=utf-8");
    require_once 'Classes/PHPExcel.php';
    require_once 'inc/connection.php';
    //Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    $loadfile = $_POST['filename'];
    require_once 'Classes/PHPExcel.php';
    $objPhpexel = PHPExcel_IOFactory::load('table.xlsx');
    foreach ($objPhpexel->getWorksheetIterator() as $key){
        $lists[] = $key->toArray();
    }


    $i = 0;
    foreach ($lists as $list) { //Перебор массивов
        foreach ($list as $row) { //перебор строк
            if ($i == 0) {
                $i++;
                continue;
            }
            $row[2] =  date('Y.m.d', strtotime($row[2]));
            $query = "INSERT INTO timetable VALUES (NULL,'{$row[1]}','{$row[2]}','{$row[3]}','{$row[4]}','{$row[5]}','{$row[6]}','{$row[7]}','{$row[8]}','{$row[9]}','{$row[10]}')";
            /*
            $query ="INSERT INTO timetable VALUES (";
            foreach ($row as $col) { //Перебор значения в строке
                 $query = $query."'".$col."',";
            }
            $query = substr($query,0,-1); //Удаление дву последних символов
            $query = $query.")"
            ;*/
            echo $query.'<br>';
            mysqli_query($connection,$query); // Выполнение SQL запроса
        }

    }




