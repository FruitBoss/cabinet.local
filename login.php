<?php
    session_start(); // Начало php сессии
    header("Content-Type: text/html; charset=utf-8"); //Заголовок кодировки
    require_once 'inc/connection.php'; //одключение connnection.php
    require_once 'inc/function.php';
    //Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    if (!$connection) {
        die($connection);
    }
    $connection->set_charset('utf8');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Личный кабинет (BETA)</title>
</head>
<body id="login_div">
<!-- Форма отправки логина и пароля -->
   <img class="banner img-responsive center-block" src="img/%D0%91%D0%B0%D0%BD%D0%BD%D0%B5%D1%80.png" alt="">
   <h1 class="logotype">Оренбургский филиал</h1>

   <h3  class="logotype">личный кабинет студента</h3>
    <div id="login">
        <form class="form-horizontal" action="login.php" method="post">
            <label for="inputUser" class="control-label">Логин:</label>
            <input class="form-control" type="text" name="user" value="" required="required" placeholder="Введите логин">
            <label for="inputPassword" class="control-label">Пароль:</label>
            <input class="form-control" type="password" name="password" value="" required="required" placeholder="Введите пароль">
            <br>
            <input class="btn btn-primary" type="submit" name="login" value="Авторизация">
        </form>
    </div>
    <p id="copyright">Об ошибках пишите на почту chserg81@yandex.ru</p>
<?php
    if (isset($_POST['login'])){
        //Извлечение из базы данных логин и пароль
        $user = stripcslashes($_POST['user']);
        $user = mysqli_real_escape_string($connection, $user);
        $query = "SELECT * FROM login WHERE studentID={$user}";
      //  echo $query."<br>";
        //echo mysql_fix_string($_POST['user']);
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Databased filed");
        }
        $row = mysqli_fetch_assoc($result);
        echo "<br>";
        //Проверка пароля
        $password = stripcslashes($_POST['password']);
        $password = mysqli_real_escape_string($connection, $password);
        $salt = '2017';
        if (md5($password.$salt) == $row['password']) {
           // echo $row['admin'];
            if ($row['admin'] == 1) {
                $_SESSION['admin'] = $_POST['login'];

                echo '<script type="text/javascript">document.location.href="admin.php"</script>';
            }
            else {
                $_SESSION['id'] = $_POST['user'];
                $_SESSION['password'] = $row['password'];
                echo '<script type="text/javascript">document.location.href="main.php"</script>';
            }
        }
        else {
            echo "Пароль не верен";
        }
    }
?>
</body>
</html>
