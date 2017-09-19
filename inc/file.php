<?php

$uploaddir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'portfolio/153139'.DIRECTORY_SEPARATOR; //Получаем корневую директорию сайта и назначаем папку для загрузки файлов:
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']); //Считываем загружаемый файл:


/*Проверяем загружен ли файл.
В соответствии с входящими данными назначаем сопровождающее сообщение.
Если файл не загружен, загружаем в директорию указанную в $uploadfile:*/
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    $out = "Файл корректен и был успешно загружен.\n";
} else {
    $out = "Возможная атака с помощью файловой загрузки!\n";
}

echo $out;

?>