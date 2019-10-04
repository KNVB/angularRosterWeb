<?php
  class ITO
  {
    private $dbo;
    public $availableShiftList=[];
    public $blackListedShiftPatternList=[];
    public $itoId;
    public $joinDate;
    public $lastMonthBalance;
    public $leaveDate;

    public $name;
    public $postName;
    public $previousMonthShiftList=[];

    public $shiftList=[];

    public $thisMonthBalance;
    public $workingHourPerDay;

    public function __construct() {

    }
    public function getITOList($rosterYear,$rosterMonth) {
      $this->dbo=new Dbo();
      $itoList=$this->dbo->getITOList($rosterYear,$rosterMonth);
      $this->dbo->close();
      return $itoList;
    }
  }
?>
