<?php
 require("Dbo.php");
 require("RosterRule.php");
 header('Content-Type: application/json; charset=utf-8');


 $rosterRule=new RosterRule();
 echo json_encode($rosterRule);

?>
