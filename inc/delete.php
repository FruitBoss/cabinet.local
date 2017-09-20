<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 20.09.2017
 * Time: 20:18
 * скрипт удаления файлов студента с портфолио
 */
   // $delete = substr($_POST['file_delete'],33);
    unlink($_SERVER['DOCUMENT_ROOT']."/".$_POST['file_delete']);
//    //echo $delete;
//echo $_POST['file_delete'];
//echo "<pre>";
//print_r($_SERVER);
//echo "<pre>";