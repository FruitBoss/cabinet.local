<?php
    //Функция выборки результатов сессии
    function statement($object,$number){
        foreach ($object as $it) {
            foreach ($object as $x){
                if($x['sessions_idsessions']==$number) {
                    echo "<h4>Семестр {$number}</h4><br>";
                    echo "<table class='table table-bordered'><tr>";
                    foreach ($object as $it) {
                        echo "<tr>";
                        if ($it['sessions_idsessions'] == $number) {
                            echo "<td>".$it['subjects_list_subjects']."</td>";
                            echo "<td>".$it['scores_scores']."</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                    break;
                }
            }
        }
    }


//Фуннкция конвертирования названия дня недели
function week_day($day)
{
    $wd="";
    switch ($day) {
        case 'Monday':
            $wd = "Понедельник";
            break;
        case  'Tuesday':
            $wd = "Вторник";
            break;
        case  'Wednesday':
            $wd = "Среда";
            break;
        case  'Thursday':
            $wd = "Четверг";
            break;
        case  'Friday':
            $wd = "Пятница";
            break;
        case  'Saturday':
            $wd = "Суббота";
            break;
        case  'Sunday':
            $wd = "Воскресенье";
            break;
    }
    return $wd;
}
