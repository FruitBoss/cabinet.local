<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
require_once 'Classes/PHPExcel.php';
require_once 'inc/connection.php';
//Соединение с базой данных
$connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
$loadfile = $_POST['filename'];
require_once 'Classes/PHPExcel.php';
$objPhpexel = PHPExcel_IOFactory::load('stud.xlsx');
foreach ($objPhpexel->getWorksheetIterator() as $key){
    $lists[] = $key->toArray();
}
//echo '<pre>';
//print_r($lists);
//echo '</pre>';
$i = 0;
foreach ($lists as $list) { //Перебор массивов
    foreach ($list as $row) { //перебор строк
        if ($i == 0) {
            $i++;
            continue;
        }
        $query1 = "INSERT INTO login VALUES (NULL,{$row[6]},'{$row[6]}',0)";
        $row[5] =  date('Y.m.d', strtotime($row[5])); //Дата рождения
        $row[12] =  date('Y.m.d', strtotime($row[12])); //Дата заключения договора
        $row[14] =  date('Y.m.d', strtotime($row[14])); //Дата зачисления
        $row[16] =  date('Y.m.d', strtotime($row[16])); //Дата отчисления
        $query2 = "INSERT INTO personal_data VALUES (NULL,'{$row[1]}','{$row[2]}','{$row[3]}','{$row[4]}','{$row[5]}',{$row[6]},'{$row[7]}','{$row[8]}','{$row[9]}','{$row[10]}','{$row[11]}','{$row[12]}','{$row[13]}','{$row[14]}','{$row[15]}','{$row[16]}','{$row[17]}','{$row[18]}','{$row[19]}','{$row[20]}')";




      /*  $query2 ="INSERT INTO personal_data VALUES (";
        foreach ($row as $col) { //Перебор значения в строке

            $query2 = $query2."'".$col."',";
        }
        $query2 = substr($query2,0,-1); //Удаление двух последних символов
        
        $query2 = $query2.")";
      */
        echo $query1.'<br>';
        echo $query2.'<br>';

        mysqli_query($connection,$query1); // Выполнение SQL запроса
        mysqli_query($connection,$query2); // Выполнение SQL запроса
    }

}




