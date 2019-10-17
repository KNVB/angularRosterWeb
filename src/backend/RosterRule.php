<?php
 require("Dbo.php");
 class RosterRule{
  private $dbo;
  public $essentialShiftList=[];
  public $maxConsecutiveWorkingDay=0;
  public $shiftHourCount=[];
  public function __construct(){

    $escapChar=chr(27);
    $this->dbo=new Dbo();
    $result=$this->dbo->getRosterRule();


    $temp=$result["shiftList"][0];
    $temp=str_replace("essential".$escapChar,"",$temp);
    $this->essentialShiftList=explode(",",$temp);
    $temp=$result["ConsecutiveWorkingDay"][0];
    $temp=str_replace("max".$escapChar,"",$temp);
    $this->maxConsecutiveWorkingDay=$temp;
    foreach($result["shiftHour"] as $key=> $value) {
      $tempArray=explode($escapChar,$value);
      $this->shiftHourCount[$tempArray[0]]= $tempArray[1];
    }

    $this->dbo->close();
  }
 }
?>
