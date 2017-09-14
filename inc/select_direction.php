<?php
    // Подключение connection.php
    require_once 'connection.php';
    // Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    $connection->set_charset('utf8');
    $query = "SELECT * FROM direction";
    $result =  $connection->query($query);
    echo "<select id='selectTraining' class=\"selectpicker col-xs-8 direction\" name=\"addTraining\" data-live-search=\"true\">";
    foreach ($result as $key) {
    echo "<option value='{$key['code']}'>{$key['code']}</option>";
    }
    echo "</select>";
?>
<script>
    //$('.selectpicker').selectpicker();
    $('select').selectpicker();
</script>
