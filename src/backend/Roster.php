<?php

class Roster
{
  private $dbo;
  private $year;
  private $month;
  public $itoRosterList=[];
  public $itoList=[];
  public function __construct($year,$month)
	{
    $this->month=$month;
    $this->year=$year;
  }
  public function getITORosterList($itoIdList)
  {
    $this->dbo=new Dbo();
    $result=$this->dbo->getITORosterList($this->year,$this->month, $itoIdList);
    $this->dbo->close();
    return $result;
  }
}
?>
