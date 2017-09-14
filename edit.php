<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
include 'connection.php';
//Соединение с базой данных
$connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Редактировать личные данные</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<?php
$query = "SELECT * FROM personal_data WHERE login_studentID = {$_SESSION['id']}";
//echo $query;
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);
?>
<div>
    <h3 class="personal_account">Личный кабинет студента</h3>
    <h4 class="personal_account"><?= $row['lastName']." ".$row['firstName']." ".$row['patronymic']?></h4>
    <p class="personal_account">Редактирование личных данных</p>
    <hr>
    <div class="data">
            <p>Пол:</p>
            <p>Направление обучения</p>
            <p>Название направления</p>
            <p>Группа</p>
            <p>Номер зачетной книжки</p>
            <p>Форма обучения</p>
            <p>Зачисление</p>
    </div>

    <div class="data">
        <form action="edit.php" method="post">
            <p>Мужской &nbsp <input type="radio" name="gender" value="male" checked> &nbsp&nbsp&nbsp Женский  &nbsp<input type="radio" name="gender" value="female"></p>


        </form>

    </div>


</div>



</body>
</html>