<?php
require("DbInfo.php");
require("Dbo.php");
require("ITO.php");
require("ITORoster.php");
require("Roster.php");
require("RosterTable.php");
require("DateUtility.php");
header('Content-Type: application/json; charset=utf-8');

if (isset($_GET["year"]) && isset($_GET["month"]))
{
  $year=intval ($_GET["year"]);
  $month=intval ($_GET["month"]);
}
else
{
  $month = intval (date('m'));
  $year = intval (date('Y'));
}
$dateUtility=new DateUtility();
$monthlyCalendar=$dateUtility->getMonthlyCalendar($year,$month);
for ($i=count($monthlyCalendar->dateObjList)+1;$i<32;$i++)
{
  $monthlyCalendar->dateObjList[$i]="null";
}

$ito=new ITO();
$roster=new Roster($year,$month);
$itoRosterList=$roster->getITORosterList($ito->getITOList($year,$month));

$rosterTable=new RosterTable();
$rosterTable->monthlyCalendar = $monthlyCalendar;
$rosterTable->itoRosterList = $itoRosterList;
echo json_encode($rosterTable);

?>
