<?php
    session_start(); // Начало php сессии
    require_once '../inc/connection.php'; //одключение connection.php
    //Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    if (!$connection) {
        die($connection);
    }
    $connection->set_charset('utf8');
  //  echo  "<form action=\"admin.php\" method=\"post\">";
    if(isset($_SESSION['i']) == false) {
        $_SESSION['i'] = 0;
    }
    for ($i = 0; $i < $_POST['count']; $i++) {
        $j = 0;
        echo <<<_END
<tr id="">
                            <td>
                                <div>
                                    <select id="" name="save[{$_SESSION['i']}][{$j}]" data-live-search="true">
_END;

        $j++;
        $query = 'SELECT idsessions FROM sessions';
        $result = $connection->query($query);
        foreach ($result as $key) {
            echo "<option>{$key['idsessions']}</option>";
        }    
        echo <<<_END
                                </select>
                                </div>

                            </td>
                            <td>
                                <div>
                                    <select id="" name="save[{$_SESSION['i']}][{$j}]" data-live-search="true">
_END;
        $j++;
        $query = 'SELECT subjects FROM subjects_list';
        $result = $connection->query($query);
        foreach ($result as $key) {
            echo "<option>{$key['subjects']}</option>";
        }
        echo <<<_END
      </select>
                                </div>

                            </td>
                            <td>
                                <div>
                                    <select id="" name="save[{$_SESSION['i']}][{$j}]" data-live-search="true">
_END;


        $query = 'SELECT scores FROM scores';
        $result = $connection->query($query);
        foreach ($result as $key) {
            echo "<option>{$key['scores']}</option>";
        }
        echo <<<_END

                                  
                                    </select>
                                </div>

                            </td>
                        </tr>
_END;
        $_SESSION['i']++;
    }
   // echo "</form>";
    unset($_POST['count']);
?>
<script>
  //  $('.selectpicker').selectpicker();
    $('select').selectpicker();
</script>
