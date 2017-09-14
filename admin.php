<?php
    session_start(); // Начало php сессии
    require_once 'inc/connection.php'; //одключение connection.php
    //Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    if (!$connection) {
        die($connection);
    }
    $connection->set_charset('utf8');


    if (isset($_GET['record_data']) && ($_GET['record_data'] == true)) {
        $_GET['group_'] = strtr($_GET['group_'],'-','_');
        $_GET['brithdate'] = date('Y-m-d', strtotime($_GET['brithdate']));
        $_GET['date_contract'] = date('Y-m-d', strtotime($_GET['date_contract']));
        $_GET['date_enrollment'] = date('Y-m-d', strtotime($_GET['date_enrollment']));
        $_GET['date_contributions'] = date('Y-m-d', strtotime($_GET['date_contributions']));
        $query2 = "UPDATE personal_data SET lastName = '{$_GET['edit_last_name']}', firstName = '{$_GET['edit_first_name']}', patronymic='{$_GET['edit_patronymic']}', gender='{$_GET['gender']}', brithdate='{$_GET['brithdate']}', training_status='{$_GET['training_status']}', group_='{$_GET['group_']}', financing_source='{$_GET['financing_source']}', enrollment='{$_GET['enrollment']}', contract_number='{$_GET['contract_number']}', date_contract='{$_GET['date_contract']}', command_enrollment='{$_GET['command_enrollment']}', date_enrollment='{$_GET['date_enrollment']}', command_contributions='{$_GET['command_contributions']}', date_contributions='{$_GET['date_contributions']}', nationality='{$_GET['nationality']}', higher_education='{$_GET['higher_education']}', phone_number='{$_GET['phone']}', email='{$_GET['email']}' WHERE login = {$_GET['edit_id_student']}";
        //echo $query;
        unset($_GET['record_data']);
        $connection->query($query);
    }
    // Скрипт удаления студента
    if (isset($_GET['deletePeronalData'])) {
        $query_1 = "DELETE FROM personal_data WHERE id = {$_GET['deletePeronalData']}";
       // $query_2 = "DELETE FROM statement WHERE student_id = {$_GET['deletePeronalData']}";
        $query_3 = "DELETE FROM  login WHERE id = {$_GET['deletePeronalData']}";
       // echo $query_1 . "<br>";
        //echo $query_2;
        $connection->query($query_1);
        $connection->query($query_3);
        unset($_GET['deletePeronalData']);
        if ((isset($_GET['sel'])) && (isset($_GET['student']))) {
            echo "<script>location.href='admin.php?sel={$_GET['sel']}&student={$_GET['student']}';</script>";
        }
        elseif  (isset($_GET['sel'])) {
            echo "<script>location.href='admin.php?sel={$_GET['sel']}';</script>";

        }
        elseif (isset($_GET['student'])) {
            echo "<script>location.href='admin.php?student={$_GET['student']}';</script>";
        }
        else {
            echo "<script>location.href='admin.php';</script>";
        }
    }

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Режим редактирования</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css">
    <!--Подключение внешних js скриптов-->
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="js/defaults-ru_RU.min.js"></script>
</head>
<body>
    <!--  Модеальное окно вывода результатов сессий студента  -->
    <div id="myModalResultSession" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <!-- Заголовок модального контента -->
                <div class="modal-body">
                <!-- Тело мобального контента -->
                    <div class="container-fluid">
                        <p>Результаты сессии у студента</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="closeResultSession" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Модальное окна колчичества записей оценок -->
    <div id="myModalCount" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <!-- Заголовок модального контента -->
                <div class="modal-body">
                    <!-- Основное содержимое окна редактирования направлений -->
                    <div class="container-fluid">
                        <h5 style="display: inline-block; width: 145px" class="col-xs-11">Введите кол-во предметов</h5>
                        <input style="display: inline-block; width: 47px; margin-top: 10px" id="inputRow" type="text" class="form-control col-xs-1" name="editCodeStuednt"  maxlength="2">
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Содержимое подвала -->
                    <button id="clearCount" type="button" class="btn btn-danger btn-sm">Очистить форму</button>
                    <button id="saveCount" type="button" class="btn btn-primary btn-sm">Ввести</button>
                    <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Модальное окна при успешной записи -->
    <div id="myModalBox" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <!-- Основное содержимое модального окна успешной записи -->
                <div class="modal-body">
                    Запись проведена успешно
                </div>
            </div>
        </div>
    </div>
    <!-- Модальное окно редактирования успеваемости -->
    <div id="myModalRedactor" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <form method="post" id="formx" action="javascript:void(null);" onsubmit="call()">
                        <input id="id_student" type="hidden" name="id_student" value="">
                    <div style="text-align: center"><h5 id="lastName" class="student">Фамилия</h5><h5 id="firstName" class="student">Имя</h5><h5 id="patronym" class="student">Отчество</h5></div>
                    <table id="tableScories" class="table table-striped table-hover">
                        <tr><th>Семестр</th><th>Предмет</th><th>Оценка</th></tr>

                    </table>
                </div>
                <div class="modal-footer">
                    <button id="saveScories" type="button" class="btn btn-primary">Сохранить</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Модальное окна редактирования студента -->
    <div id="myModalStudent" class="modal fade">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Основное содержимое модального окна редактирования студента -->
                <div class="modal-body">
                    <!-- Что бы в мобальном окне распологать блоки -->
                    <div class="container-fluid bd-example-row">
                        <div>
                            <div class="col-xs-4">
                                <h5>Код студента</h5>
                                <input id="codeStudent" type="text" class="form-control" name="editCodeStuednt"  maxlength="6">
                                <h5>Код зачетной книжки</h5>
                                <input id="editIDStudent" type="text" class="form-control" name="editIDStudent"  maxlength="6">
                                <h5>Группа</h5>
                                <select id="editGroup" class="selectpicker col-xs-12" name="code" data-live-search="true">
                                    <?php
                                    $query = "SELECT * FROM groups";
                                    $result = mysqli_query($connection, $query);
                                    foreach ($result as $key) {
                                        echo "<option value=\"".strtr($key['group_name'],"_","-")."\">".strtr($key['group_name'],"_","-")."</option>";
                                    }
                                    ?>
                                </select>
                                <h5>Направление</h5>
                                <select name="editTraining" id="editTraining" class="selectpicker col-xs-12" data-live-search="true">
                                    <?php
                                    $query = "SELECT * FROM direction";
                                    $result = mysqli_query($connection, $query);
                                    foreach ($result as $key) {
                                        echo "<option value=\"".$key['code']."\">".$key['code']."</option>";
                                    }
                                    ?>
                                </select>
                                <h5>Статус</h5>
                                <select name="editEnrollment" id="editEnrollment" class="selectpicker col-xs-12" data-live-search="true">
                                    <?php
                                    $query = "SELECT * FROM enrollment";
                                    $result = $connection->query($query);
                                    foreach ($result as $key) {
                                        echo "<option value='{$key['status']}'>{$key['status']}</option>";
                                    }
                                    ?>
                                </select>
                                <h5>Источник финанс</h5>
                                <select name="editFinancing" id="editFinancing" class="selectpicker col-xs-12" data-live-search="true">
                                    <?php
                                    $query = "SELECT * FROM financing_source";
                                    $result = $connection->query($query);
                                    foreach ($result as $key) {
                                        echo "<option value='{$key['financing_source']}'>{$key['financing_source']}</option>";
                                    }
                                    ?>
                                </select>

                            </div>

                            <div class="col-xs-4">
                                <h5>Фамилия</h5>
                                <input id="editLastName" type="text" class="form-control" name="editLastName"  maxlength="15">
                                <h5>Имя</h5>
                                <input id="editFirstName" type="text" class="form-control" name="editFirstName"  maxlength="15">
                                <h5>Отчество</h5>
                                <input id="editPatronymic" type="text" class="form-control" name="editPatronymic" maxlength="15">
                                <h5>Пол</h5>
                                <select name="editGender" id="editGender" class="selectpicker col-xs-12" data-live-search="true">
                                    <?php
                                    $query = "SELECT * FROM gender";
                                    $result = $connection->query($query);
                                    foreach ($result as $key) {
                                        echo "<option value='{$key['id_gender']}'>{$key['id_gender']}</option>";
                                    }
                                    ?>
                                </select>
                                <h5>Дата рождения</h5>
                                <input id="editBrithdate" type="text" class="form-control" name="editBrithdate" maxlength="15">
                                <h5>Гражданство</h5>
                                <select name="editNationality" id="editNationality" class="selectpicker col-xs-12" data-live-search="true">
                                    <?php
                                    $query ="SELECT * FROM nationality";
                                    $result = $connection->query($query);
                                    foreach ($result as $key) {
                                        echo "<option value='{$key['citizenship']}'>{$key['citizenship']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-xs-4">
                                <h5>Номер договора</h5>
                                <input id="editContractNumber" type="text" class="form-control" name="editContractNumber" maxlength="15">
                                <h5>Дата договора</h5>
                                <input id="editDateContract" type="text" class="form-control" name="editDateContract" maxlength="15">
                                <h5>Приказ зачисления</h5>
                                <input id="editCommandEnrollment" type="text" class="form-control" name="editCommandEnrollment" maxlength="15">
                                <h5>Дата зачисления</h5>
                                <input id="editDateEnrollment" type="text" class="form-control" name="editDateEnrollment" maxlength="15">
                                <h5>Приказ отчисления</h5>
                                <input id="editCommandContributions" type="text" class="form-control" name="editCommandContributions" maxlength="15">
                                <h5>Дата отчисления</h5>
                                <input id="editDateContribution" type="text" class="form-control" name="editDateContribution" maxlength="15">
                            </div>
                            <div class="col-xs-12">
                                <h5>Образование</h5>
                                <input id="editEducation" type="text" class="form-control" name="editEducation"  maxlength="100">

                            </div>
                            <div class="col-xs-6">
                                <h5>Электронная почта</h5>
                                <input id="editEmail" type="text" class="form-control" name="editEmail"  maxlength="15">
                            </div>
                            <div class="col-xs-6">
                                <h5>Номер телефона</h5>
                                <input id="editPhone" type="text" class="form-control" name="editPhone"  maxlength="20">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="deletePersonalData" type="button" class="btn btn-danger">Удалить данные</button>
<!--                    <button id="Redactor" type="button" class="btn btn-primary">Успеваемость</button>-->
                    <button id="savePersonalData" type="button" class="btn btn-primary">Сохранить изменения</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>

                </div>
            </div>
        </div>
    </div>

<!-- Вызов модального окна редкатирования направления -->
    <div id="myModalTraining" class="modal fade">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <!-- Заголовок модального контента -->
                <div class="modal-body">
                <!-- Основное содержимое окна редактирования направлений -->
                    <div class="container-fluid">
                        <div class="row" style="position: relative">
                            <div  class="col-xs-5">
                                <h5>Код направления</h5>
                                <div id="s">

                                </div>
                            </div>
                            <div class="col-xs-7 direction">
                                <h5>Название направления</h5>
                                <p id="nameDirection"></p>
                            </div>
                            <div class="clearfix hidden-xs hidden-sm"></div>

                            <div class="col-xs-3 direction" style="left: 14px">
                                <input id="direct" type="text" class="form-control">
                            </div>
                            <div id="direct_name" class="col-xs-9">
                                <input type="text" class="form-control">
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <!-- Содержимое подвала -->
                    <button id="addDirection" type="button" class="btn btn-default">Добавить</button>
                    <button id="" type="button" class="btn btn-default">Удалить</button>
                    <button id="closeDirection" type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
<!--  Вызов модального окна редактирования группы  -->
    <div id="myModalTraining" class="modal fade">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Заголовок модального контента -->
                <div class="modal-body">
                    <!-- Основное содержимое окна редактирования направлений -->
                    <div class="container-fluid">
                        <div class="row">
                            <div  class="col-xs-5">
                                <h5>Код направления</h5>
                                <div id="s">

                                </div>
                            </div>
                            <div class="col-xs-7 direction">
                                <h5>Название направления</h5>
                                <p id="nameDirection"></p>
                            </div>
                            <div class="clearfix hidden-xs hidden-sm"></div>

                            <div class="col-xs-3 direction">
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-xs-9">
                                <input type="text" class="form-control">
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <!-- Содержимое подвала -->
                    <button id="" type="button" class="btn btn-default">Добавить</button>
                    <button id="" type="button" class="btn btn-default">Удалить</button>
                    <button id="closeDirection" type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Вызов модального окна добавления студента -->
    <div id="addModalStudent" class="modal fade">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Основное содержимое модального окна добавления студента -->
                <div class="modal-body">
                    <!-- Что бы в мобальном окне распологать блоки  -->
                    <div class="container-fluid bd-example-row">
                        <div>
                             <form class="form-horizontal" action="admin.php" method="post">
                                  <table class="">
                                  <!-- Фамилия -->
                                  <tr>
                                      <td><h5>Фамилия</h5></td>
                                      <td><input type="text" class="form-control" name="lastName" placeholder="Введите фамилию" maxlength="45"> </td>
                                  </tr>
                                  <!-- Имя -->
                                  <tr>
                                      <td><h5>Имя</h5></td>
                                      <td><input type="text" class="form-control" name="firstName" placeholder="Введите имя" maxlength="45"></td>
                                  </tr>
                                  <!-- Отчество -->
                                  <tr>
                                      <td><h5>Отчество</h5></td>
                                      <td><input type="text" class="form-control" name="patronymic" placeholder="Введите отчество" maxlength="45"></td>
                                  </tr>
                                  <!-- Дата рождения -->
                                  <tr>
                                      <td><h5>Дата рождения</h5></td>
                                      <td><input type="text" class="form-control datetimepicker" name="birthdate"></td>
                                  </tr>
                                  <!-- Пол -->
                                  <tr>
                                      <td><h5>Пол</h5></td>
                                      <td>
                                          <select class="selectpicker" name="gender">
                                               <?php
                                                   $query = "SELECT * FROM gender";
                                                   $result = $connection->query($query);
                                                   foreach ($result as $key) {
                                                      echo "<option value='{$key['id_gender']}'>{$key['id_gender']}</option>";
                                                   }
                                               ?>
                                          </select>
                                      </td>
                                  </tr>
                                  <!-- Гражданство -->
                                  <tr>
                                     <td><h5>Гражданство</h5></td>
                                     <td>
                                         <select id="studentNationality" class="selectpicker" name="citizenship" data-live-search="true">
                                             <?php
                                                 $query = "SELECT * FROM nationality";
                                                 $result = mysqli_query($connection, $query);
                                                 foreach ($result as $key) {
                                                    echo "<option>".$key['citizenship']."</option>";
                                                 }
                                             ?>
                                         </select>
                                     </td>
                                  </tr>

                                  <!-- Группа -->
                                  <tr>
                                      <td><h5>Группа</h5></td>
                                      <td>
                                      <select class="selectpicker" name="groups" data-live-search="true">
                                          <?php
                                               $query = "SELECT * FROM groups";
                                               $result = mysqli_query($connection, $query);
                                               foreach ($result as $key) {
                                                   echo "<option>".strtr($key['group_name'],"_","-")."</option>";
                                               }
                                          ?>
                                      </select>
                                      </td>
                                  </tr>
                                  <!-- Направление обучения -->
                                  <tr>
                                      <td><h5>Направление обучения</h5></td>
                                      <td>
                                          <select class="selectpicker" name="code" data-live-search="true">
                                              <?php
                                                   $query = "SELECT * FROM direction;";
                                                   $result = mysqli_query($connection, $query);
                                                   foreach ($result as $key) {
                                                       echo "<option>".$key['code']."</option>";
                                                   }
                                              ?>
                                          </select>
                                      </td>
                                  </tr>
                                  <!-- Номер зачетной книжки -->
                                  <tr>
                                      <td><h5>Номер зач. книжки</h5></td>
                                      <td> <input type="text" class="form-control" name="number" placeholder="Номер" maxlength="6"></td>
                                  </tr>
                                  <!-- Источник финансирования -->
                                  <tr>
                                      <td><h5>Финансирование</h5></td>
                                      <td>
                                          <select class="selectpicker" name="finansing">
                                              <?php
                                                  $query = "SELECT * FROM financing_source";
                                                  $result = $connection->query($query);
                                                  foreach ($result as $key) {
                                                    echo "<option value='{$key['financing_source']}'>{$key['financing_source']}</option>";
                                                  }
                                              ?>
                                          </select>
                                      </td>
                                  </tr>
                                  <!-- Статус зачисления -->
                                  <tr>
                                      <td><h5>Зачисление</h5></td>
                                        <td>
                                               <select class="selectpicker" name="status">
                                                       <?php
                                                           $query = "SELECT * FROM enrollment";
                                                           $result = $connection->query($query);
                                                           foreach ($result as $key) {
                                                               echo "<option value='{$key['status']}'>{$key['status']}</option>";
                                                           }
                                                      ?>
                                           </select>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td><h5>Номер догов.</h5></td>
                                          <td> <input type="text" class="form-control" name="numberContract" placeholder="Номер договор" maxlength="6"></td>
                                      </tr>
                                      <tr>
                                          <td><h5>Дата договора</h5></td>
                                          <td><input type="text" class="form-control datetimepicker" name="dateContract"></td>
                                      </tr>
                                      <tr>
                                          <td><h5>Код зачисления</h5></td>
                                          <td><input type="text" class="form-control" name="codeEnrollment" placeholder="Код зачисления" maxlength="6"></td>
                                      </tr>
                                      <tr>
                                          <td><h5>Дата зачисления</h5></td>
                                          <td><input type="text" class="form-control datetimepicker" name="dateEnrollment"></td>
                                      </tr>
                                      <tr>
                                          <td><h5>Об образовании</h5></td>
                                          <td><input type="text" class="form-control" name="education" placeholder="Об образовании" maxlength="100"></td>
                                      </tr>
                                      <tr>
                                          <td><h5>Номер телефона</h5></td>
                                          <td><input type="text" class="form-control" name="numberPhone" placeholder="Номер телефона" maxlength="20"></td>
                                      </tr>
                                      <tr>
                                          <td><h5>Email</h5></td>
                                          <td><input type="text" class="form-control" name="email" placeholder="Email" maxlength="15"></td>
                                      </tr>
                                  </table>
                             </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <input id="" type="submit" class="btn btn-primary" name="record" value="Сохранить">

                </div>
                </form>
            </div>
        </div>
    </div>

    <div>
        <h3 class="personal_account">Режим редактирования</h3>
        <h4 class="personal_account" >Методист</h4>
        <form id="" action="login.php" method="post">
            <input id="exit" class="btn btn-primary" type="submit" value="Выход" name="exit">
        </form>
    </div>


    <ul id="mytab" class="nav nav-tabs">
        <li><a data-toggle="tab" href="#panel1">Статистика</a></li>
        <li><a data-toggle="tab" href="#panel2">Редактор студентов</a></li>

        <li><a data-toggle="tab" href="#">Редактор расписния</a></li>
    </ul>

    <div class="tab-content">
    <!-- Панель 1, редактор студента -->
    <div id="panel1" class="tab-pane fade in active">
    <p>Статистика</p>
<!--


    Тут будет страница статистики


-->
    </div>
    <!-- Панель 2, редактор студента -->
        <div id="panel2" class="tab-pane fade">
        <br>
            <div class="conteiner">
                <div id="row" class="row">

<!-- Инициализация виджета "Bootstrap datetimepicker" -->
    <script type="text/javascript">
         $(function() {
              $('.datetimepicker').datetimepicker({
                   language: 'ru',
                   pickTime: false
              });
              $('#datetimepicker2').datetimepicker({
                   language: 'ru',
                   pickTime: false
              });
              $('#editBrithdate').datetimepicker({
                   language: 'ru',
                   pickTime: false
              });
              $('#editDateContract').datetimepicker({
                   language: 'ru',
                   pickTime: false
              });
              $('#editDateEnrollment').datetimepicker({
                   language: 'ru',
                   pickTime: false
              });
              $('#editDateContribution').datetimepicker({
                   language: 'ru',
                   pickTime: false
              });
         });
    </script>


                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Управление списками</div>
                            <div class="panel-body">

                                    <div class="row">
                                        <button id="addTraining" class="col-xs-2 btn btn-default" type="button">Добавить направление</button>
                                        <button class="col-xs-2 btn btn-default" type="button">Добавить группу</button>
                                        <button id="addStudent" class="col-xs-2 btn btn-default" type="button">Добавить студента</button>
                                        <button class="col-xs-2 btn btn-default" type="button">Добавить гражданство</button>
                                        <button class="col-xs-2 btn btn-default" type="button">Добавить оценки</button>
                                        <button class="col-xs-2 btn btn-default" type="button">Добавить сессию</button>
                                        <button class="col-xs-2 btn btn-default" type="button">Добавить предмет</button>
                                    </div>
                            </div>
                        </div>
                    </div>
                        <div class="col-xs-12 col-xs-offset-0">
                            <div class="panel panel-default">
                                <div class="panel-heading">Все студенты</div>
                                <div class="panel-body">
<!-- Список всех студентов -->
<?php
    $query_all = "SELECT lastName, firstName, patronymic, group_, birthdate FROM personal_data";
    $connection_all = mysqli_query($connection,$query_all);
?>
<!-- Выбор группы в цикле -->
<form role="form" class="form-inline" method="get">
    <div class="form-group">
        <select id="groups" class="selectpicker" data-live-search="true">
        <?php
        $query = "SELECT * FROM groups";
        $result = mysqli_query($connection, $query);
        if (isset($_GET['sel'])) { //Если выбор уже был то выбранная группа удаляется
            echo "<option>".$_GET['sel']."</option>";
            foreach ($result as $key) {
                if ($_GET['sel'] == strtr($key['group_name'],"_","-")) {
                    continue;
                }
                echo "<option>".strtr($key['group_name'],"_","-")."</option>";
            }
        }
        else {
            echo "<option value=\"all\">Все группы</option>";
            foreach ($result as $key) {
            $gr = $key['group_name'];
            echo "<option>".strtr($key['group_name'],"_","-")."</option>";
            }
        }
        ?>
        </select>
        <input id="search" type="text" class="form-control" placeholder="Поиск фамилии" value="<?=$_GET['student']?>">
        <button id="clear" type="button" class="btn btn-default">Очистить</button>
    </div>
</form>
<!-- Выбор группы в цикле -->
<br>
<!-- Пагинация всех студентов -->
<?php
    echo "<br>";
if (isset($_GET['student']) && isset($_GET['sel'])) {
    $gr = strtr($_GET['sel'],'-', '_');
    $query = "SELECT * FROM personal_data WHERE group_ = '{$gr}' AND lastName LIKE '{$_GET['student']}%'";
}
elseif (isset($_GET['student'])) {
    $query = "SELECT * FROM personal_data WHERE lastName LIKE '{$_GET['student']}%'";
}
elseif ($_GET['sel']) {
    $gr = strtr($_GET['sel'],'-', '_');
    $query = "SELECT * FROM personal_data WHERE group_ = '{$gr}'";
}
 else {
    $query = "SELECT * FROM personal_data";
}
    $result = $connection->query($query); //ООП запрос
echo '<pre>'.print_r($result).'</pre>';
    $row = mysqli_fetch_row($result);
//echo print_r($row);
    if ($result) {
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
    if (isset($_GET['student']) && isset($_GET['sel'])) {
       $gr = strtr($_GET['sel'],'-', '_');
       $query = "SELECT * FROM personal_data WHERE group_ = '{$gr}' AND lastName LIKE '{$_GET['student']}%' LIMIT {$start}, {$end}";
    }
    elseif (isset($_GET['sel'])) {
        $gr = strtr($_GET['sel'],'-', '_');
        $query = "SELECT * FROM personal_data WHERE group_ = '{$gr}' LIMIT {$start}, {$end}";
    }
    elseif (isset($_GET['student'])) {
        $student = $_GET['student'];
        $query = "SELECT * FROM personal_data WHERE lastName LIKE '{$student}%' LIMIT {$start}, {$end}";
    }
    else {
        $query = "SELECT * FROM personal_data LIMIT {$start}, {$end}";
       }

    $result = mysqli_query($connection, $query); // выполнить запрос
    $row = mysqli_fetch_assoc($result);
    echo "<table id='st' class='table table-striped table-hover'>";
    echo "<tr><th>№</th><th>Направление подготовки</th><th>Группа</th><th>Номер з/к</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Дата рождения</th><th>Пол</th><th>Статус</th><th>Успеваемость</th></tr>";
    $i = $start + 1;
    foreach ($result as $row) {
        $row['group_'] = strtr($row['group_'],'_','-');
        echo "<tr class='cursorPointer'><td id='phone_number' class='hidetd'>{$row['phone_number']}</td><td id='higher_education' class='hidetd'>{$row['higher_education']}</td><td id='date_contributions' class='hidetd'>{$row['date_contributions']}</td><td id='command_contributions' class='hidetd'>{$row['command_contributions']}</td><td id='date_enrollment' class='hidetd'>{$row['date_enrollment']}</td><td id='command_enrollment' class='hidetd'>{$row['command_enrollment']}</td><td id='date_contract' class='hidetd'>{$row['date_contract']}</td><td id='contract_number' class='hidetd'>{$row['contract_number']}</td><td id='nationality' class='hidetd'>{$row['nationality']}</td><td id='gender' class='hidetd'>{$row['gender']}</td><td id='financing_source' class='hidetd'>{$row['financing_source']}</td><td id='training_status' class='hidetd' >{$row['training_status']}</td><td id='idStudent' class='hidetd'>{$row['id']}</td><td>$i</td><td>{$row['training_status']}</td><td id='group'>{$row['group_']}</td><td id='login'>{$row['login']}</td><td id='lastName'>{$row['lastName']}</td><td id='firstName'>{$row['firstName']}</td><td id='Patronymic'>{$row['patronymic']}</td><td id='Brithdate'>{$row['brithdate']}</td><td>{$row['gender']}</td><td id='enrollment'>{$row['enrollment']}</td><td><button class='view btn btn-default col-xs-10'>Просмотр</button><button id='' class='edit btn btn-default col-xs-10'>Редактировать</button></td></tr>";
        $i++;
    }
    echo "</table>";
    }
 else {
         echo "<table class='table table-striped table-hover'>";
    echo "<tr><th>№</th><th>Группа</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Дата рождения</th></tr>";
    echo "</table>";
     echo "<script>$('#mytab a[href=\"#panel2\"]').tab('show');</script>";
}
?>
    <div class="">
        <form action="admin.php" method="post">
     <?
        for ($i = 1; $i < $pages+1; $i++){
            echo "<input type='submit' value='{$i}' name='page'>";
        }
        echo "<script>$('#mytab a[href=\"#panel2\"]').tab('show');</script>";
     ?>
        </form>
    </div>
             </div>
                </div>
                    </div>

<!-- Запись нового студента -->
<?php
    if (isset($_POST['record'])) {
        $query_rec = "INSERT INTO login VALUES (NULL,{$_POST['number']},'{$_POST['number']}',NULL )";
        $connection->query($query_rec); //Запись в таблицу login
        $_POST['groups'] = strtr($_POST['groups'],'-','_'); //Замена тире на подчеркивание
        $_POST['dateContract'] = date('Y-m-d', strtotime($_POST['dateContract'])); //Перевод строки в дату
        $_POST['birthdate'] = date('Y-m-d', strtotime($_POST['birthdate'])); //Перевод строки в дату
        $_POST['dateEnrollment'] = date('Y-m-d', strtotime($_POST['dateEnrollment'])); //Перевод строки в дату
        $query_rec = "INSERT INTO personal_data VALUES (NULL,\"{$_POST['lastName']}\",\"{$_POST['firstName']}\",\"{$_POST['patronymic']}\",\"{$_POST['gender']}\",\"{$_POST['birthdate']}\",{$_POST['number']},\"{$_POST['code']}\",\"{$_POST['groups']}\",\"{$_POST['finansing']}\",\"{$_POST['status']}\",\"{$_POST['numberContract']}\",\"{$_POST['dateContract']}\",\"{$_POST['codeEnrollment']}\",\"{$_POST['dateEnrollment']}\",NULL,NULL,\"{$_POST['citizenship']}\",\"{$_POST['education']}\",\"{$_POST['numberPhone']}\",\"{$_POST['email']}\")";
        echo $query_rec;
        $connection->query($query_rec); //Запись в таблицу personal_data

        //Вызов модального окна
            echo " <script>".
            "$(document).ready(function () {".
            "$(\"#myModalBox\").modal('show');".
            "});
            </script>";
        //Закрытие модального окна
        echo <<<_END
         <script>
            $(document).ready(function () {
                 setInterval(function(){
                 $("#myModalBox").modal('hide');
                 },2000)
                 });
        </script>
_END;
        unset($_POST['record']);
        echo "<script>location.href='admin.php'</script>";
        echo "<script>$('#mytab a[href=\"#panel2\"]').tab('show');</script>";
        }
?>
                    </div>
                </div>
            </div>
        </div>
<!-- Скрипт работает при выборе студента -->
<script>
     $(document).ready(function () {
         $('#groups').on('change', function () {
             if ($('#search').val() == '') {
                 location.href = "admin.php?sel=" + $('#groups option:selected').val(); //Передача знчений с select
                 var sel = true;
                 $('#mytab a[href=\"#panel2\"]').tab('show');
             }
             else {
                 location.href = "admin.php?sel=" + $('#groups option:selected').val() + "&student=" + $('#search').val();
                 $('#mytab a[href=\"#panel2\"]').tab('show');
             }
         });
         $('#search').on('change', function(){
             if ($('#groups').val() == 'all') {
                 location.href = "admin.php?student=" + $('#search').val();
                 $('#mytab a[href=\"#panel2\"]').tab('show');
             }
             else {
                 location.href = "admin.php?sel=" + $('#groups option:selected').val() + "&student=" + $('#search').val();
             }
         });
         $('#clear').click(function () {
                 location.href="admin.php";
         });
         $('#st tr').on('dblclick', function(){
            $('#codeStudent').val($(this).find('#idStudent').text());
            $('#editLastName').val($(this).find('#lastName').text());
            $('#editIDStudent').val($(this).find('#login').text());
            var selectGroups = $(this).find('#group').text();
            $('#editGroup').selectpicker('val',selectGroups);
            $('#editFirstName').val($(this).find('#firstName').text());
            $('#editPatronymic').val($(this).find('#Patronymic').text());
            $('#editBrithdate').val($(this).find('#Brithdate').text());
            var training = $(this).find('#training_status').text();
            $('#editTraining').selectpicker('val',training);
            var enrolment = $(this).find('#enrollment').text();
            $('#editEnrollment').selectpicker('val',enrolment);
            var finansing = $(this).find('#financing_source').text();
            $('#editFinancing').selectpicker('val',finansing);
            var gender = $(this).find('#gender').text();
            $('#editGender').selectpicker('val',gender);
            var nationality = $(this).find('#nationality').text();
            $('#editNationality').selectpicker('val',nationality);
            $('#editContractNumber').val($(this).find('#contract_number').text());
            $('#editDateContract').val($(this).find('#date_contract').text());
            $('#editCommandEnrollment').val($(this).find('#command_enrollment').text());
            $('#editDateEnrollment').val($(this).find('#date_enrollment').text());
            $('#editCommandContributions').val($(this).find('#command_contributions').text());
            $('#editDateContribution').val($(this).find('#date_contributions').text());
            $('#editEducation').val($(this).find('#higher_education').text());
            $('#editPhone').val($(this).find('#phone_number').text());
            $('#myModalStudent').modal('show');
         });
         //Сохранение персональных данных
         $('#savePersonalData').on('click', function(){
             <?php
                if ((isset($_GET['sel'])) && (isset($_GET['student']))) {
                    echo <<<_END
                location.href="admin.php?sel={$_GET['sel']}&student={$_GET['student']}&record_data=true&id="+$('#codeStudent').val()+"&edit_id_student="+$('#editIDStudent').val()+"&edit_last_name="+$('#editLastName').val()+"&edit_first_name="+$('#editFirstName').val()+"&edit_patronymic="+$('#editPatronymic').val()+"&gender="+$('#editGender').val()+"&brithdate="+$('#editBrithdate').val()+"&training_status="+$('#editTraining').val()+"&group_="+$('#editGroup').val()+"&financing_source="+$('#editFinancing').val()+"&financing_source="+$('#editFinancing').val()+"&enrollment="+$('#editEnrollment').val()+"&contract_number="+$('#editContractNumber').val()+"&date_contract="+$('#editDateContract').val()+"&command_enrollment="+$('#editCommandEnrollment').val()+"&date_enrollment="+$('#editDateEnrollment').val()+"&command_contributions="+$('#editCommandContributions').val()+"&date_contributions="+$('#editDateContribution').val()+"&nationality="+$('#editNationality').val()+"&higher_education="+$('#editEducation').val()+"&email="+$('#editEmail').val()+"&phone="+$('#editPhone').val();
                $('#myModalStudent').modal('hide');
_END;

                }
                elseif  (isset($_GET['sel'])) {
                    echo <<<_END
                 location.href="admin.php?sel={$_GET['sel']}&record_data=true&id="+$('#codeStudent').val()+"&edit_id_student="+$('#editIDStudent').val()+"&edit_last_name="+$('#editLastName').val()+"&edit_first_name="+$('#editFirstName').val()+"&edit_patronymic="+$('#editPatronymic').val()+"&gender="+$('#editGender').val()+"&brithdate="+$('#editBrithdate').val()+"&training_status="+$('#editTraining').val()+"&group_="+$('#editGroup').val()+"&financing_source="+$('#editFinancing').val()+"&financing_source="+$('#editFinancing').val()+"&enrollment="+$('#editEnrollment').val()+"&contract_number="+$('#editContractNumber').val()+"&date_contract="+$('#editDateContract').val()+"&command_enrollment="+$('#editCommandEnrollment').val()+"&date_enrollment="+$('#editDateEnrollment').val()+"&command_contributions="+$('#editCommandContributions').val()+"&date_contributions="+$('#editDateContribution').val()+"&nationality="+$('#editNationality').val()+"&higher_education="+$('#editEducation').val()+"&email="+$('#editEmail').val()+"&phone="+$('#editPhone').val();
                 $('#myModalStudent').modal('hide');
_END;

                }
                elseif (isset($_GET['student'])) {
                    echo <<<_END
              location.href="admin.php?student={$_GET['student']}&record_data=true&id="+$('#codeStudent').val()+"&edit_id_student="+$('#editIDStudent').val()+"&edit_last_name="+$('#editLastName').val()+"&edit_first_name="+$('#editFirstName').val()+"&edit_patronymic="+$('#editPatronymic').val()+"&gender="+$('#editGender').val()+"&brithdate="+$('#editBrithdate').val()+"&training_status="+$('#editTraining').val()+"&group_="+$('#editGroup').val()+"&financing_source="+$('#editFinancing').val()+"&financing_source="+$('#editFinancing').val()+"&enrollment="+$('#editEnrollment').val()+"&contract_number="+$('#editContractNumber').val()+"&date_contract="+$('#editDateContract').val()+"&command_enrollment="+$('#editCommandEnrollment').val()+"&date_enrollment="+$('#editDateEnrollment').val()+"&command_contributions="+$('#editCommandContributions').val()+"&date_contributions="+$('#editDateContribution').val()+"&nationality="+$('#editNationality').val()+"&higher_education="+$('#editEducation').val()+"&email="+$('#editEmail').val()+"&phone="+$('#editPhone').val();
              $('#myModalStudent').modal('hide');
_END;
                }
                else {
                    echo <<<_END
              location.href="admin.php?record_data=true&id="+$('#codeStudent').val()+"&edit_id_student="+$('#editIDStudent').val()+"&edit_last_name="+$('#editLastName').val()+"&edit_first_name="+$('#editFirstName').val()+"&edit_patronymic="+$('#editPatronymic').val()+"&gender="+$('#editGender').val()+"&brithdate="+$('#editBrithdate').val()+"&training_status="+$('#editTraining').val()+"&group_="+$('#editGroup').val()+"&financing_source="+$('#editFinancing').val()+"&financing_source="+$('#editFinancing').val()+"&enrollment="+$('#editEnrollment').val()+"&contract_number="+$('#editContractNumber').val()+"&date_contract="+$('#editDateContract').val()+"&command_enrollment="+$('#editCommandEnrollment').val()+"&date_enrollment="+$('#editDateEnrollment').val()+"&command_contributions="+$('#editCommandContributions').val()+"&date_contributions="+$('#editDateContribution').val()+"&nationality="+$('#editNationality').val()+"&higher_education="+$('#editEducation').val()+"&email="+$('#editEmail').val()+"&phone="+$('#editPhone').val();
              $('#myModalStudent').modal('hide');
_END;
                }
             ?>


         });
         $('#deletePersonalData').on('click',function(){
             <?php
             if ((isset($_GET['sel'])) && (isset($_GET['student']))) {
                 echo <<<_END
                location.href="admin.php?sel={$_GET['sel']}&student={$_GET['student']}&deletePeronalData="+$('#codeStudent').val();
                $('#myModalStudent').modal('hide');
_END;

             }
             elseif  (isset($_GET['sel'])) {
                 echo <<<_END
                 location.href="admin.php?sel={$_GET['sel']}&deletePeronalData="+$('#codeStudent').val();
                 $('#myModalStudent').modal('hide');
_END;

             }
             elseif (isset($_GET['student'])) {
                 echo <<<_END
              location.href="admin.php?student={$_GET['student']}&deletePeronalData="+$('#codeStudent').val();
              $('#myModalStudent').modal('hide');
_END;
             }
             else {
                 echo <<<_END
              location.href="admin.php?deletePeronalData="+$('#codeStudent').val();
              $('#myModalStudent').modal('hide');
_END;
             }
             ?>
         });
        // Добавление нового студента
        $('#addStudent').on('click', function (){
            $('#studentNationality').selectpicker('val','Россия');
            $('#addModalStudent').modal('show');
        });
        $('.cursorPointer').hover(function(){
            $(this).css('cursor','pointer');
        });

        // Окно кол-ва предметов
         $('.edit').click(function () {
            $('#myModalCount').modal('show');
             //alert($(this).closest('tr').find('#lastName').text());
             $('#lastName').text($(this).closest('tr').find('#lastName').text());
             $('#firstName').text($(this).closest('tr').find('#firstName').text());
             $('#patronym').text($(this).closest('tr').find('#Patronymic').text());
             $('#id_student').attr('value',$(this).closest('tr').find('#login').text());

         });

         // Вызов окна добавления успеваемости студента
         $('#saveCount').click(function () {
             $.ajax({
                 type: "POST",
                 url: "/inc/arrRow.php",
                 dataType: "html",
                 data: "count="+$('#inputRow').val(),
              //   dataType: "html",
                 success: function (data) {
                     $('#tableScories').append(data);
//                       alert($('#sessions_0').val());
                 }
             });



             $('#myModalRedactor').modal('show');
         });
        // $('#myModalRedactor').modal('show');
         $('#addTraining').on('click',function(){
             $.ajax({
                type:'POST',
                 url:'/inc/select_direction.php',
                 success: function (content) {
                    //alert(content);
                    $('#s').append(content);
                 }
             });
             $('#myModalTraining').modal('show');
         });


         // Просмотреть оценки
         $('.view').on('click', function () {
//             alert($(this).closest('tr').find('#login').text());
            var id_student = $(this).closest('tr').find('#login').text();
            $.ajax({
                type:"POST",
                url:"/inc/resultSession.php",
                data: "id="+id_student,
                success: function (data) {
                    $('#myModalResultSession .container-fluid').append(data);
                }

             
            });
            $('#myModalResultSession').modal('show');
         });
         $('#closeResultSession').click();


         $('.selectpicker').selectpicker();
         $('select').selectpicker();

         // Отправка сериализованной формы
         $('#saveScories').on('click',function () {
             var msg = $('#formx').serialize();
             $.ajax({
                type: 'POST',
                 url: '/inc/saveScories.php',
                 data: msg,
                 success: function (data) {
                   // alert(data);
                    $('body').append(data);
                 }
             });
         });

         // Очиста формы ввода оценок
         $('#clearCount').click(function () {
             $('#tableScories').empty().append('<tr><th>Семестр</th><th>Предмет</th><th>Оценка</th></tr>');
             $.ajax({
                 type: "POST",
                 url: '/inc/clear.php',
                 success: function () {
                     alert('Очищено');
                 }
             });

         });

         //Событие изменения направления
         $('body').on('change', '#selectTraining', function () {
         //    alert($('#selectTraining option:selected').val());
             var direction = $('#selectTraining option:selected').val();
             $.ajax({
                 type:'POST',
                 url: '/inc/direction.php',
                 data: 'direction='+direction,
                 success: function (data) {
       //              alert(data);
                     $('#nameDirection').text(data);
                 }
             }); //конец ajax
         });

         // Удаление <select> при закрытии модального окна редактирования направлений
         $('#closeDirection').on('click', function () {
            $('#s').empty(); // 277
         });
         //Собитие при нажатии кнопки добавления направления
         $('#addDirection').click(function () {
             var direct = $('#direct').val();
             var direct_name = $('#direct_name').val();
             alert(direct+" "+direct_name);
             $.ajax({
                type: 'POST',
                url: '/inc/adDirection.php',
                data: {direct:direct, direct_name:direct_name},
                 success: function (data) {
                    alert(data);
                 }
             });
         });
     });
</script>
</body>
</html>