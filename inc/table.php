<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
require_once '../Classes/PHPExcel.php';
require_once '../inc/connection.php'; //одключение connnection.php
require_once "../inc/function.php";
//Соединение с базой данных
$connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
$connection->set_charset('utf8');
$phpexcel = new PHPExcel();
$page = $phpexcel->setActiveSheetIndex(0);
$page->setTitle("Расписание занятий");
$page->getColumnDimension('A')->setWidth(16);
$page->getColumnDimension('B')->setWidth(50);
$page->getColumnDimension('C')->setWidth(20);
$page->getColumnDimension('D')->setWidth(15);
$page->getColumnDimension('E')->setWidth(15);
$page->getColumnDimension('F')->setWidth(15);
$page->getColumnDimension('G')->setWidth(16);
$page->getColumnDimension('H')->setWidth(17);
$page->setCellValue("A1", 'Группа');
$page->setCellValue("B1", $_SESSION['group']);
$page->getStyle("A1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00CED1');
$page->getStyle("B1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00CED1');
$count = 2;
//Первая неделя
$query_timetable="SELECT * FROM timetable WHERE week = 1 AND groups = '{$_SESSION['group']}'";
$result_timetable = mysqli_query($connection,$query_timetable);
$page->setCellValue("A{$count}", 'Первая неделя');
$page->getStyle("A{$count}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
$count++;
$page->setCellValue("A{$count}","Дата");
$page->setCellValue("B{$count}","Дисциплина");
$page->setCellValue("C{$count}","Начало");
$page->setCellValue("D{$count}","Окончание");
$page->setCellValue("E{$count}","Аудитория");
$page->setCellValue("F{$count}","Кол-во часов");
$page->setCellValue("G{$count}","Преподаватель");
$page->setCellValue("H{$count}","Форма контроля");
$page->getStyle("A{$count}:H{$count}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7B68EE');
$count++;
$next = '';
foreach ($result_timetable as $key1) {
    if ($next == $key1['date']) {
        $count--;
        continue;
    }
    else {
        $next = $key1['date'];
        $page->setCellValue("A{$count}",$key1['date']);
        $page->getStyle("A{$count}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
        $count++;
        $query_timetable = "SELECT * FROM timetable WHERE date = '{$key1['date']}' AND groups = '{$_SESSION['group']}'";
        $result_timetable = mysqli_query($connection, $query_timetable);
        foreach ($result_timetable as $key2) {
            $page->setCellValue("B{$count}","{$key2['subject']}");
            $page->setCellValue("C{$count}","{$key2['time_start']}");
            $page->getStyle("C{$count}")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
            $page->setCellValue("D{$count}","{$key2['time_stop']}");
            $page->getStyle("D{$count}")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
            $page->setCellValue("E{$count}","{$key2['lecture_room']}");
            $page->setCellValue("F{$count}","{$key2['hours_lecture']}");
            $page->setCellValue("G{$count}","{$key2['lector']}");
            $page->setCellValue("H{$count}","{$key2['control_form']}");
            $count++;
        }
        $count++;
    }
}
//Вторая неделя
$query_timetable="SELECT * FROM timetable WHERE week = 2 AND groups = '{$_SESSION['group']}'";
$result_timetable = mysqli_query($connection,$query_timetable);

// $count = 2;
$page->setCellValue("A1", 'Группа');
$page->setCellValue("B1", $_SESSION['group']);
$page->setCellValue("A{$count}", 'Вторая неделя');
$page->getStyle("A{$count}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
$count++;
$page->setCellValue("A{$count}","Дата");
$page->setCellValue("B{$count}","Дисциплина");
$page->setCellValue("C{$count}","Начало");
$page->setCellValue("D{$count}","Окончание");
$page->setCellValue("E{$count}","Аудитория");
$page->setCellValue("F{$count}","Кол-во часов");
$page->setCellValue("G{$count}","Преподаватель");
$page->setCellValue("H{$count}","Форма контроля");
$page->getStyle("A{$count}:H{$count}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7B68EE');
$count++;
$next = '';
foreach ($result_timetable as $key1) {
    if ($next == $key1['date']) {
        $count--;
        continue;
    }
    else {
        $next = $key1['date'];
        $page->setCellValue("A{$count}",$key1['date']);
        $page->getStyle("A{$count}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('4682B4');
        $count++;
        $query_timetable = "SELECT * FROM timetable WHERE date = '{$key1['date']}' AND groups = '{$_SESSION['group']}'";
        $result_timetable = mysqli_query($connection, $query_timetable);
        foreach ($result_timetable as $key2) {
            $page->setCellValue("B{$count}","{$key2['subject']}");
            $page->setCellValue("C{$count}","{$key2['time_start']}");
            $page->getStyle("C{$count}")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
            $page->setCellValue("D{$count}","{$key2['time_stop']}");
            $page->getStyle("D{$count}")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
            $page->setCellValue("E{$count}","{$key2['lecture_room']}");
            $page->setCellValue("F{$count}","{$key2['hours_lecture']}");
            $page->setCellValue("G{$count}","{$key2['lector']}");
            $page->setCellValue("H{$count}","{$key2['control_form']}");
            $count++;
        }
        $count++;
    }
}




header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition:attachment;filename='Расписание_занятий.xlsx'");
$objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
$objWriter->save('php://output');
unset($_POST['download_results_session']);




