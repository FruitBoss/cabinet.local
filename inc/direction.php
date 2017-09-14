<?php
    // Подключение connection.php
    require_once 'connection.php';
    // Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    $connection->set_charset('utf8');
//echo $_POST['direction']."<br>";
    $query = "SELECT name FROM direction WHERE code = '{$_POST['direction']}'";

    //echo $query;
    //$query = "SELECT name FROM direction WHERE code = '09.03.03'";
    $result = $connection->query($query);
    $row = mysqli_fetch_assoc($result);
    echo "Направление: ".$row['name'];
?>