<?php
    session_start(); // Начало php сессии
    require_once '../inc/connection.php'; //одключение connection.php
    //Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    if (!$connection) {
        die($connection);
    }
    $connection->set_charset('utf8');
    //echo print_r($_POST);

    foreach ($_POST['save'] as $i) {
        $query = "INSERT INTO statement VALUES (NULL,{$_POST['id_student']},{$i[0]},'{$i[1]}','{$i[2]}')";

    echo  $query."<br>";
    $connection->query($query);

}
?>