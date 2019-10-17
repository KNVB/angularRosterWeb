<?php
  require("DateUtility.php");
  header('Content-Type: application/json; charset=utf-8');

  if( (isset($_POST['year']) ) && (isset($_POST['month']) ))
  {
    $year=intval ($_POST["year"]);
    $month=intval ($_POST["month"]);
  }
  else {
    $month = intval (date('m'));
    $year = intval (date('Y'));
  }
  $dateUtility=new DateUtility();
  $monthlyCalendar=$dateUtility->getMonthlyCalendar($year,$month);

  for ($i=count($monthlyCalendar->dateObjList)+1;$i<32;$i++)
  {
    $monthlyCalendar->dateObjList[$i]="null";
  }

  echo json_encode($monthlyCalendar);

?>
