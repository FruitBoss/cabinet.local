<?php
session_start(); // Начало php сессии
include 'inc/connection.php'; //одключение connnection.php в которой содержится база, пользователь, пароль

$connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname); //Соединение с базой данных
  if (!$connection) { //Если $connection не true то остановить
    die($connection);
  }
$connection->set_charset('utf8'); //Установка шрифта
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Pagination</title>
</head>
<body>
<!------------------------------ Пагинация всех студентов ------------------------------------------------------------->
<?php
    $query = "SELECT * FROM personal_data";
    $result = $connection->query($query); //ООП запрос
    $end = 35; // количество ресурсов на странице
    $pages = (mysqli_num_rows($result) / $end); //Кол-во страниц в пагинации
    if (isset($_POST["page"])) {
       // то получить его и присвоить значение переменной
       $page  = $_POST["page"];
    } else {
       // иначе переменной page присвоить значение 1
       $page = 1;
      };
    // начиная с какой строки необходимо возвращать данные
    $start = ($page - 1) * $end;
    // sql-запрос (LIMIT опредяляет какой диапазон необходимо выбрать)
    $query = "SELECT lastName, firstName, patronymic, `group`, birthdate FROM personal_data LIMIT {$start}, {$end}"; // $start сколько строк пропустить, $end сколько строк показать
    echo $query;
    $result = mysqli_query($connection, $query); // выполнить запрос
    echo "<table class='table table-striped table-hover'>";
    echo "<tr><th>№</th><th>Группа</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Дата рождения</th></tr>";
    $i = $start + 1;
    foreach ($result as $row) {
    echo "<tr><td>$i</td><td>{$row['group']}</td><td>{$row['lastName']}</td><td>{$row['firstName']}</td><td>{$row['patronymic']}</td><td>{$row['birthdate']}</td></tr>";
        $i++;
    }
    echo "</table>";
?>
    <div>
        <form action="pagination.php" method="post">
        <?
            for ($i = 1; $i < $pages+1; $i++){
                echo "<input type='submit' value='{$i}' name='page'>";
            }
            ?>
        </form>
    </div>
<!------------------------------ Пагинация всех студентов ------------------------------------------------------------->
</body>
</html>

    

