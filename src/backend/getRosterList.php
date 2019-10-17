<?php
  require("Dbo.php");
  require("ITO.php");
  require("ITORoster.php");
  require("Roster.php");
  header('Content-Type: application/json; charset=utf-8');

  if (isset($_POST["year"]) && isset($_POST["month"]))
  {
    $year=intval ($_POST["year"]);
    $month=intval ($_POST["month"]);
  }
  else
  {
    $month = intval (date('m'));
    $year = intval (date('Y'));
  }


  $ito=new ITO();

  $roster=new Roster($year,$month);
  echo json_encode($roster->getITORosterList($ito->getITOList($year,$month)));
  /*
  $rosterTable=new RosterTable($year,$month);
  $rosterTable->build();
  echo json_encode($rosterTable);
  */
?>
