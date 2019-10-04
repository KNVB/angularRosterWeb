<?php
    header('Content-Type: application/json; charset=utf-8');
    $month = intval (date('m'));
    $year = intval (date('Y'));
    header('Content-Type: application/json; charset=utf-8');
    echo ('{"year":'.$year.',"month":'.$month.'}');
?>
