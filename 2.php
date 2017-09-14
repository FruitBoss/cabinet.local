<?php
header("Content-Type: text/html; charset=utf-8");
setlocale(LC_ALL, 'russian');
$day = strftime("%A", strtotime('2017-01-01'));
echo $day;