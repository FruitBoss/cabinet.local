<?php
    session_start();
    header("Content-Type: text/html; charset=utf-8");
    require_once 'Classes/PHPExcel.php';
    require_once 'inc/connection.php'; //одключение connnection.php
    require_once "inc/function.php";
    //Соединение с базой данных
    $connection = mysqli_connect($dbhost, $dblogin, $dbpass, $dbname);
    $connection->set_charset('utf8');
    //$query_subject = "SELECT * FROM statement WHERE student_id = {$_SESSION['id']}";
    //$result_subject = mysqli_query($connection, $query_subject);
 $phpexcel = new PHPExcel();
 $page = $phpexcel->setActiveSheetIndex(0);
 $page->setTitle("Результаты сессий");
 $page->getColumnDimension('A')->setWidth(50);
 $page->getColumnDimension('B')->setWidth(8);
 //$count = 2;
 $page->setCellValue("A1",$_SESSION['lastName'].' '.$_SESSION['firstName'].' '.$_SESSION['patronymic']);
// $page->setCellValue("B1",);
// $page->setCellValue("C1",);
 //$count = 3;
//$page->setCellValue("A2", 'Оценка');
    $count = 2;
    for ($i = 1; $i < 8; $i++) {
        $query = "SELECT * FROM statement WHERE student_id = {$_SESSION['id']} AND sessions_idsessions = {$i}";
        $result_subject = $connection->query($query);
        if ($num_rows = mysqli_num_rows($result_subject) == 0) {
            continue;
        }
        else {
            $page->setCellValue("A{$count}", "Семестр ".$i);
            $page->getStyle("A{$count}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00CED1');
            $count++;
            foreach ($result_subject as $key) {
                $page->setCellValue("A{$count}", $key['subjects_list_subjects']);
                $page->setCellValue("B{$count}", $key['scores_scores']);
                $count++;
            }
        }

    }

 header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
 header("Content-Disposition:attachment;filename='test.xlsx'");
 $objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
 $objWriter->save('php://output');
 unset($_POST['download_results_session']);
 
 
                    

