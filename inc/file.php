<?php
    session_start(); // Начало php сессии
    require_once '../inc/connection.php'; //одключение connection.php
    //Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    if (!$connection) {
        die($connection);
    }
    $connection->set_charset('utf8');

    //$uploaddir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'portfolio/153139'.DIRECTORY_SEPARATOR; //Получаем корневую директорию сайта и назначаем папку для загрузки файлов:
    $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/portfolio/153139/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']); //Считываем загружаемый файл:

    /*Проверяем загружен ли файл.
    В соответствии с входящими данными назначаем сопровождающее сообщение.
    Если файл не загружен, загружаем в директорию указанную в $uploadfile:*/
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        $out = "Файл корректен и был успешно загружен.\n";
    } else {
        $out = "Возможная атака с помощью файловой загрузки!\n";
    }

    echo $out."<br>";

    $query = "INSERT INTO portfolio VALUES (NULL,'{$_POST['name']}','{$uploadfile}',{$_POST['login']})";
    $connection->query($query);
    echo  $query."<br>Файлы в суперглобальном массиве POST<br>";
    print_r($_POST);
    echo "<br>".$uploadfile."<br>".$uploaddir;



?>