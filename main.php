<?php
    session_start();
    header("Content-Type: text/html; charset=utf-8");
    require_once 'Classes/PHPExcel.php';
    require_once 'inc/connection.php'; //одключение connnection.php
    require_once "inc/function.php";
    //Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    $connection->set_charset('utf8');
// Если пользователь не авторизован то перенаправляется на страницу авторизаци
    if ((isset($_SESSION['id']) === false) && (isset($_SESSION['password'])===false)) {
        echo '<script type="text/javascript">document.location.href="login.php"</script>';
    }
// Изменение пароля
    if (isset($_GET['password'])) {
      
        $chekPassword = "SELECT password FROM login WHERE studentID = '{$_SESSION['id']}'";
        $chekResult = $connection->query($chekPassword);
        $chekRow = mysqli_fetch_assoc($chekResult);
        $_GET['oldpassword'] = stripcslashes($_GET['oldpassword']); // Функция stripcslashes удаляет экранирование символов
        $_GET['oldpassword'] = mysqli_real_escape_string($connection, $_GET['oldpassword']);
        $solt = '2017';
        if ($chekRow['password'] == md5($_GET['oldpassword'].$solt)) {
            $newpassword = md5($_GET['newpassword'].$solt);
            $chekPassword = "UPDATE login SET password = '{$newpassword}' WHERE studentID = '{$_SESSION['id']}'";
            //echo $chekPassword;
            $connection->query($chekPassword);

        }
        else {
            echo  <<<_END
            <script>
                alert('Введеный пароль не верный');
            </script>
_END;
        }
        echo "<script>location.href='main.php';</script>";
    }

//Изменение номера телефна
    if (isset($_GET['newphone'])) {
        $_GET['newphone'] = stripcslashes($_GET['newphone']);
        $_GET['newphone'] = mysqli_real_escape_string($connection,$_GET['newphone']);
        $query = "UPDATE personal_data SET phone_number = '{$_GET['newphone']}' WHERE login = {$_SESSION['id']}";
        echo $query;
        $connection->query($query);
        echo "<script>location.href='main.php';</script>";
    }


// Изменние email
    if (isset($_GET['email'])) {
        $_GET['email'] = stripcslashes($_GET['email']);
        $_GET['email'] = mysqli_real_escape_string($connection, $_GET['email']);
        $query = "UPDATE personal_data SET email = '{$_GET['email']}' WHERE login = {$_SESSION['id']}";
        $connection->query($query);
        echo "<script>location.href='main.php';</script>";
    }
    

    
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Личный кабинет студента</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Подключение внешних js скриптов -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.file-input.js"></script>
</head>
<body>
<!-- Вызов модального окна изменения пароля -->
    <div id="myModalPassword" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <!-- Основное содержимое модального окна редактирования пароля -->
                <div class="modal-body">
                    <form class="form-horizontal" action="" method="post">
                        <table class="table">
                            <tr>
                                <td><h5>Пароль</h5></td>
                                <td><input id="oldPassword" type="password" class="form-control" name="oldPassword" placeholder="Текущий" maxlength="18"></td>
                            </tr>
                            <tr>
                                <td><h5>Новый пароль</h5></td>
                                <td><input id="newPassword" type="password" class="form-control" name="newPassword" placeholder="Новый пароль" maxlength="18"></td>
                            </tr>
                            <tr>
                                <td><h5>Повторить пароль</h5></td>
                                <td><input id="copyPassword" type="password" class="form-control" name="copyPassword" placeholder="Повторить пароль" maxlength="18"></td>
                            </tr>
                        </table>
                    </form>
                    <h5 id="password" style="text-align: center">Пароли не совпадают</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button id="savePassword" type="button" class="btn btn-primary">Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>

<!--Вызов модального окна изменения номера телефона -->
<div id="myModalTelephone" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <!-- Основное содержимое модального окна редактирования пароля -->
            <div class="modal-body">
                <h4>Изменить номер телефона</h4>
                <input id="newPhone" type="text" class="form-control" maxlength="20">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button id="saveTelephone" type="button" class="btn btn-primary">Сохранить изменения</button>
            </div>
        </div>
    </div>
</div>

<!-- Вызов модального окна изменения емайла -->
<div id="myModalEmail" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <!-- Основное содержимое модального окна редактирования пароля -->
            <div class="modal-body">
                <h4>Изменить адрес email</h4>
                <input id="newEmail" type="text" class="form-control" maxlength="20">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button id="saveEmail" type="button" class="btn btn-primary">Сохранить изменения</button>
            </div>
        </div>
    </div>
</div>
<?php
    $query = "SELECT * FROM personal_data WHERE login = {$_SESSION['id']}";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    $_SESSION['lastName'] = $row['lastName'];
    $_SESSION['firstName'] = $row['firstName'];
    $_SESSION['patronymic'] = $row['patronymic'];
?>
    <div>
        <h3 class="personal_account">Личный кабинет студента</h3>
        <h4 class="personal_account"><?= $row['lastName']." ".$row['firstName']." ".$row['patronymic']?></h4>
        <button id="changePassword" class="btn btn-default change">Изменить пароль</button>
        <button id="changeTelephone" class="btn btn-default change">Изменить номер телефона</button>
        <button id="changeEmail" class="btn btn-default change">Изменить email</button>
        <form id="exit" action="main.php" method="post">
            <input class="btn btn-primary" type="submit" value="Выход" name="exit">
        </form>
        
    </div>
    
<?php // Выход из личного кабинета
    if (isset($_POST['exit'])){
        unset($_SESSION['id']);
        unset($_SESSION['password']);
        unset($_POST['exit']);
        echo '<script type="text/javascript">document.location.href="login.php"</script>';
    }
?>

    <ul id="mytab" class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#panel1">Личные данные</a></li>
        <li><a data-toggle="tab" href="#panel2">Результаты сессии</a></li>
        <li><a data-toggle="tab" href="#panel3">Расписание занятий</a></li>
        <li><a data-toggle="tab" href="#panel4">Портфолио</a></li>
    </ul>

    <div class="tab-content">
        <div id="panel1" class="tab-pane fade in active">
             
            <div class="data">
                
                <h3>Личные данные</h3>
                   <table class="table table-bordered">
                <tr>
                    <td><strong>Пол:</strong></td>
                    <td><?= $row['gender']?></td>
                    <td><strong>Источник финансирования:</strong></td>
                    <td><?=$row['financing_source']?></td>

                </tr>
                <tr>
                    <td><strong>Дата рождения:</strong></td>
                    <td><?=$row['brithdate']?></td>
                    <td><strong>Номер договора:</strong></td>
                    <td><?=$row['contract_number']?></td>

                </tr>
                <tr>
                    <td><strong>Дата поступления:</strong></td>
                    <td><?=$row['date_enrollment']?></td>
                    <td><strong>Дата договора:</strong></td>
                    <td><?=$row['date_contract']?></td>

                </tr>
                <tr>
                    <td><strong>Направление обучения:</strong></td>
                    <td><?=$row['training_status']?></td>
                    <td><strong>Код зачисления:</strong></td>
                    <td><?=$row['command_enrollment']?></td>

                </tr>
                <tr>
                    <td><strong>Название направления:</strong></td>
                    <?php
                        $direction = "SELECT direction.name FROM direction WHERE code = '{$row['training_status']}'";
                        //echo $direction;
                        $direction_result = $connection->query($direction);
                        $row_direction = mysqli_fetch_assoc($direction_result);
                    ?>
                    <td><?=$row_direction['name']?></td>
                    <td><strong>Дата зачисления:</strong></td>
                    <td><?=$row['date_enrollment']?></td>

                </tr>
                <tr>
                    <td><strong>Группа:</strong></td>
                    <td><?=strtr($row['group_'],"_","-")?></td>
                    <td><strong>Код отчисления:</strong></td>
                    <td><?=$row['command_contributions']?></td>

                </tr>
                <tr>
                    <td><strong>Номер зачетной книжки:</strong></td>
                    <td><?=$row['login']?></td>
                    <td><strong>Дата отчисления:</strong></td>
                    <td><?=$row['date_contributions']?></td>

                </tr>

                <tr>
                    <td><strong>Зачисление:</strong></td>
                    <td><?=$row['enrollment']?></td>
                    <td><strong>Гражданство:</strong></td>
                    <td><?=$row['nationality']?></td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?=$row['email']?></td>
                    <td><strong>Номер телефона:</strong></td>
                    <td><?=$row['phone_number']?></td>
                </tr>
                <tr>

                </tr>
                <tr>
                    <td><strong>Высшее образование:</strong></td>
                    <td colspan="3"><?=$row['higher_education']?></td>
                </tr>
            </table>
            </div>
         
        </div>
        <div id="panel2" class="tab-pane fade">
            <div class="data">
                <h3>Результаты сессии</h3>
                <form action="main.php" method="post">
                    <input class="btn btn-default" type="submit" name="download_results_session" value="Загрузить">
                </form>
                <br>
                <?php //Выборка результатов сессии
                    $query_subject = "SELECT * FROM statement WHERE student_id = {$_SESSION['id']}";
                    $result_subject = mysqli_query($connection, $query_subject);

                    if (isset($_POST['download_results_session'])) {
                        unset($_POST['download_results_session']);
                        echo "<script>window.open('result_session.php', '_blank');</script>";  
                      // echo '<script>location.href="result_session.php"; </script>';
                        
                       echo "<script>$('#mytab a[href=\"#panel2\"]').tab('show');</script>";
                    }

                //Выборка первого семестра
                statement($result_subject,1);
                //Проверка второго семестра
                statement($result_subject,2);
                //Проверка третьего семестра
                statement($result_subject,3);
                //Проверка чевертого семестра
                statement($result_subject,4);
                //Проверка пятого семестра
                statement($result_subject,5);
                //Проверка шестого семестра
                statement($result_subject,6);
                ?>
            </div>
    </div>
        <div id="panel3" class="tab-pane fade">
            <div class="data">
                <h3>Расписание занятий</h3>
                <form action="main.php" method="post">
                    <input id="downTimetable" class="btn btn-default" type="submit" name="down_timetable" value="Загрузить">
                </form>
                <br>
                <?php
                    $_SESSION['group'] = $row['group_'];
                    //Выборка значений расписания 1 недели
                    $query_timetable="SELECT * FROM timetable WHERE week = 1 AND groups = '{$row['group_']}'";
                   // echo $query_timetable;
                                  $result_timetable = mysqli_query($connection,$query_timetable);
                                  if (mysqli_num_rows($result_timetable) != 0) {
                                      $group = mysqli_fetch_assoc($result_timetable);
                                      $groups = strtr($row['group_'],"_","-");
                                      echo "<h5>Группа {$groups}</h5>";
                                      echo "<h4>Первая неделя</h4><br>";
                                      $next = 0;
                                      echo "<table class='table table-bordered'>";
                                      echo "<tr><th>Дата</th><th>Дисциплина</th><th>Начало</th><th>Окончание</th><th>Аудитория</th><th>Кол-во часов</th><th>Преподаватель</th><th>Форма контроля</th></tr>";
                                      foreach ($result_timetable as $key1) {
                                          if ($next == $key1['date'])
                                              continue;
                                          else {
                                              //print_r($key1);
                                              $next = $key1['date'];
                                              $day = strftime("%A", strtotime($key1['date']));
                                              $day = week_day($day);
                                              echo "<tr><td colspan='8'>{$key1['date']} {$day}</td></tr>";
                                              $query_timetable = "SELECT * FROM timetable WHERE date = '{$key1['date']}' AND groups = '{$row['group_']}' ";
                                              //echo $query_timetable."<br>";
                                              $result_timetable = mysqli_query($connection, $query_timetable);
                                             // echo "<pre>".print_r($result_timetable)."</pre>";
                                              foreach ($result_timetable as $key2) {
                                                  echo "<tr><td></td><td>{$key2['subject']}</td><td>{$key2['time_start']}</td><td>{$key2['time_stop']}</td><td>{$key2['lecture_room']}</td><td>{$key2['hours_lecture']}</td><td>{$key2['lector']}</td><td>{$key2['control_form']}</td></tr>";
                                              }
                                          }
                                      }
                                      echo "</table>";
                                  }


                //-----------------Вторая неделя-----------------------------

                //Выборка значений расписания 2 недели
                $query_timetable="SELECT * FROM timetable WHERE week = 2 AND groups = '{$row['group_']}'";
                $result_timetable = mysqli_query($connection,$query_timetable);
                if (mysqli_num_rows($result_timetable) != 0) {
                    echo "<h4>Вторая неделя</h4><br>";
                    $next = '';
                    echo "<table class='table table-bordered'>";
                    echo "<tr><th>Дата</th><th>Дисциплина</th><th>Начало</th><th>Окончание</th><th>Аудитория</th><th>Кол-во часов</th><th>Преподаватель</th><th>Форма контроля</th></tr>";
                    foreach ($result_timetable as $key1) {
                        if ($next == $key1['date'])
                            continue;
                        else {
                            $next = $key1['date'];
                            $day = strftime("%A", strtotime($key1['date']));
                            $day = week_day($day);
                            echo "<tr><td colspan='8'>{$key1['date']} {$day}</td></tr>";
                            $query_timetable = "SELECT * FROM timetable WHERE date = '{$key1['date']}' AND groups = '{$row['group_']}'";
                            // echo $query_timetable."<br>";
                            $result_timetable = mysqli_query($connection, $query_timetable);
                            foreach ($result_timetable as $key2) {
                                echo "<tr><td></td><td>{$key2['subject']}</td><td>{$key2['time_start']}</td><td>{$key2['time_stop']}</td><td>{$key2['lecture_room']}</td><td>{$key2['hours_lecture']}</td><td>{$key2['lector']}</td><td>{$key2['control_form']}</td></tr>";
                            }
                        }
                    }
                    echo "</table>";
                }
                //---------------Третья неделя-----------------------

                //Выборка значений расписания 1 недели
                $query_timetable="SELECT * FROM timetable WHERE week = 3 AND groups = '{$row['group_']}'";
                $result_timetable = mysqli_query($connection,$query_timetable);
                if (mysqli_num_rows($result_timetable) != 0) {
                    echo "<h4>Третья неделя</h4><br>";
                    $next = '';
                    echo "<table class='table table-bordered'>";
                    echo "<tr><th>Дата</th><th>Дисциплина</th><th>Начало</th><th>Окончание</th><th>Аудитория</th><th>Кол-во часов</th><th>Преподаватель</th><th>Форма контроля</th></tr>";
                    foreach ($result_timetable as $key1) {
                        if ($next == $key1['date'])
                            continue;
                        else {
                            $next = $key1['date'];
                            $day = strftime("%A", strtotime($key1['date']));
                            $day = week_day($day);
                            echo "<tr><td colspan='8'>{$key1['date']} {$day}</td></tr>";
                            $query_timetable = "SELECT * FROM timetable WHERE date = '{$key1['date']}' AND groups = '{$row['group_']}'";
                            $result_timetable = mysqli_query($connection, $query_timetable);
                            foreach ($result_timetable as $key2) {
                                echo "<tr><td></td><td>{$key2['subject']}</td><td>{$key2['time_start']}</td><td>{$key2['time_stop']}</td><td>{$key2['lecture_room']}</td><td>{$key2['hours_lecture']}</td><td>{$key2['lector']}</td><td>{$key2['control_form']}</td></tr>";
                            }
                        }
                    }
                    echo "</table>";
                }



                //---------------Четвертая неделя-----------------------

                //Выборка значений расписания 4 недели
                $query_timetable="SELECT * FROM timetable WHERE week = 4 AND groups = '{$row['group_']}'";
                $result_timetable = mysqli_query($connection,$query_timetable);
                if (mysqli_num_rows($result_timetable) != 0) {
                    echo "<h4>Четвертая неделя</h4><br>";
                    $next = '';
                    echo "<table class='table table-bordered'>";
                    echo "<tr><th>Дата</th><th>Дисциплина</th><th>Начало</th><th>Окончание</th><th>Аудитория</th><th>Кол-во часов</th><th>Преподаватель</th><th>Форма контроля</th></tr>";
                    foreach ($result_timetable as $key1) {
                        if ($next == $key1['date'])
                            continue;
                        else {
                            $next = $key1['date'];
                            $day = strftime("%A", strtotime($key1['date']));
                            $day = week_day($day);
                            echo "<tr><td colspan='8'>{$key1['date']} {$day}</td></tr>";
                            $query_timetable = "SELECT * FROM timetable WHERE date = '{$key1['date']}' AND groups = '{$row['group_']}'";
                            $result_timetable = mysqli_query($connection, $query_timetable);
                            foreach ($result_timetable as $key2) {
                                echo "<tr><td></td><td>{$key2['subject']}</td><td>{$key2['time_start']}</td><td>{$key2['time_stop']}</td><td>{$key2['lecture_room']}</td><td>{$key2['hours_lecture']}</td><td>{$key2['lector']}</td><td>{$key2['control_form']}</td></tr>";
                            }
                        }
                    }
                    echo "</table>";
                }
                ?>
            </div>
        </div>

    <?php
    //Сохранение результат расписания
                    if (isset($_POST['down_timetable'])) {
                        echo "<script>window.open('inc/table.php', '_blank');</script>";
                        // echo '<script>location.href="result_session.php"; </script>';
                        echo "<script>location.href='main.php';</script>";
                        echo "<script>$('#mytab a[href=\"#panel3\"]').tab('show');</script>";
                    }

    ?>

<!-- Вкладка с работами студента  -->
        <div id="panel4" class="tab-pane fade">
            <div class="panel4 panel panel-default col-xs-10 col-xs-offset-1">
                <div class="panel-heading">
                    Портфолио:
                </div>
                <div class="panel-body">
                    <div class="col-xs-12">
                        <form action="" name="uploader" enctype="multipart/form-data" method="post">
                             <input type="file" name="userfile" title="Выбрать файл">
                                <div class="clearfix">
                                    <input name="" class="form-control col-xs-12 clearfix" type="text" required="required" placeholder="Введите название работы" style="width:701px"  maxlength="100">
                                </div>
                            <button type="submit" name="submit" class="btn btn-default">Отправить</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel4 panel panel-default col-xs-10 col-xs-offset-1">
                <div class="panel-heading">
                    Загруженные работы:
                </div>
                <div class="panel-body">
                    <div class="col-xs-12">

                    </div>

                </div>
            </div>
        </div>

 <script>
     $(document).ready(function(){
        // Сохранение результата сессии
//        $('#downTimetable').click(function () {
//            window.open('inc/table.php', '_blank');
//            $("#mytab a[href='#panel3']").tab('show');
//
//        });
         //Скрипты изменения пароля-------------------------------------------------------------------------------------
        $('#changePassword').on('click',function(){
             $('#password').hide();
             $('chekPassword').hide();
             $('#myModalPassword').modal('show');
         });

        $('#copyPassword').on('keyup', function(){
           var newPassword = $('#newPassword').val();
           var copyPassword = $('#copyPassword').val();
           if (newPassword !== copyPassword) {
               $('#password').show();
               
           }
           else {
               $('#password').hide();
               checkPassword = true;
           }

        });
        $('#savePassword').on('click', function(){
              //  if (checkPassword == true) {
                     $('#myModalPassword').modal('hide');
                //     checkPassword = false;
                     location.href="main.php?password=true&oldpassword="+$('#oldPassword').val()+"&newpassword="+$('#copyPassword').val();
                });
         //-------------------------------------------------------------------------------------------------------------
         //Скрипты изменения телефона-----------------------------------------------------------------------------------
         $('#changeTelephone').on('click', function(){
                $('#myModalTelephone').modal('show');
         });
         $('#saveTelephone').on('click', function(){
             $('#myModalTelephone').modal('hide');
             location.href="main.php?&newphone="+$('#newPhone').val();
         });
         //-------------------------------------------------------------------------------------------------------------
         // Скрипты изменения адреса электронной почты
         $('#changeEmail').on('click', function(){
            $('#myModalEmail').modal('show');
         });
         $('#saveEmail').on('click', function(){
             $('#myModalEmail').modal('hide');
             location.href="main.php?email="+$('#newEmail').val();
         });

         //Передача файлов в папку пользователся

                 $("form[name='uploader']").submit(function(e) {
                     //var formData = new FormData($(this)[0]);
                     var formData = new FormData(this);
                     var textData = $(this)
                     $.ajax({
                         url: '/inc/file.php',
                         type: "POST",
                         data: formData,
                         async: false,
                         success: function (msg) {
                             alert(msg);
                         },
                         error: function(msg) {
                             alert('Ошибка!');
                         },
                         cache: false,
                         contentType: false,
                         processData: false
                     });
                     e.preventDefault();
                 });
         //Оформление нкопки для загрузки файлов
         $('input[type=file]').bootstrapFileInput();
         $('.file-inputs').bootstrapFileInput();
     });
</script>
</body>
</html>