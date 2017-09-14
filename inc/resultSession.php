<?php
//    session_start();
    header("Content-Type: text/html; charset=utf-8");
    require_once '../inc/connection.php'; // Подключение connection.php
    require_once '../Classes/PHPExcel.php';
    require_once "../inc/function.php";
// Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    $connection->set_charset('utf8');

    $query_subject = "SELECT * FROM statement WHERE student_id = {$_POST['id']}";
    $result_subject = $connection->query($query_subject);
//    $result_subject = mysqli_query($connection, $query_subject);
//    echo $_POST['id'];

    //Выборка первого семестра
    statement($result_subject,1);
    //Проверка второго семестра
    statement($result_subject,2);
    //Проверка третьего семестра
    statement($result_subject,3);
    //Проверка чевертого семестра
    statement($result_subject,4);
    //Проверка пятого семестра
    statement($result_subject,5);
    //Проверка шестого семестра
    statement($result_subject,6);
?>