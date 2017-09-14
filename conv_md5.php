<?php
    require_once 'inc/connection.php'; //подключение connection.php
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    if (!$connection) {
        die($connection);
    }
    $connection->set_charset('utf8');
    $query = "SELECT password, studentID FROM login";
    $result = $connection->query($query);
    foreach ($result as $key) {
        $key['password'] = md5($key['studentID'].'2017');
        $query = "UPDATE login SET password = '{$key['password']}' WHERE studentID = {$key['studentID']}";

//        $query = "UPDATE login SET password = '{$key['studentID']}' WHERE studentID = {$key['studentID']}";
        $connection->query($query);
        echo $query."<br>";
    }