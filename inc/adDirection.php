<?php
    // Подключение connection.php
    require_once 'connection.php';
    // Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    $connection->set_charset('utf8');

    echo $_POST['direction'].$_POST['direction_name'];