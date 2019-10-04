<?php
class DateObj
{
  public $dayOfWeek;
  public $festivalInfo='';
	public $isPublicHoliday=false;
	public $isLeap=false; //閏月
  public $lunarDate;
  public $lunarMonth;
	public $lunarYear;
	public $solarDate;
	public $solarMonth;
	public $solarYear;
  public function __construct()
  {
  }
}
?>
