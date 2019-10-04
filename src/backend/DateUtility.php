<?php
  require("DateObj.php");
  require("MonthlyCalendar.php");
  class DateUtility
  {
    private $lunarInfo=[0x4bd8,0x4ae0,0xa570,0x54d5,0xd260,0xd950,0x5554,0x56af,0x9ad0,0x55d2,
        0x4ae0,0xa5b6,0xa4d0,0xd250,0xd295,0xb54f,0xd6a0,0xada2,0x95b0,0x4977,
        0x497f,0xa4b0,0xb4b5,0x6a50,0x6d40,0xab54,0x2b6f,0x9570,0x52f2,0x4970,
        0x6566,0xd4a0,0xea50,0x6a95,0x5adf,0x2b60,0x86e3,0x92ef,0xc8d7,0xc95f,
        0xd4a0,0xd8a6,0xb55f,0x56a0,0xa5b4,0x25df,0x92d0,0xd2b2,0xa950,0xb557,
        0x6ca0,0xb550,0x5355,0x4daf,0xa5b0,0x4573,0x52bf,0xa9a8,0xe950,0x6aa0,
        0xaea6,0xab50,0x4b60,0xaae4,0xa570,0x5260,0xf263,0xd950,0x5b57,0x56a0,
        0x96d0,0x4dd5,0x4ad0,0xa4d0,0xd4d4,0xd250,0xd558,0xb540,0xb6a0,0x95a6,
        0x95bf,0x49b0,0xa974,0xa4b0,0xb27a,0x6a50,0x6d40,0xaf46,0xab60,0x9570,
        0x4af5,0x4970,0x64b0,0x74a3,0xea50,0x6b58,0x5ac0,0xab60,0x96d5,0x92e0,
        0xc960,0xd954,0xd4a0,0xda50,0x7552,0x56a0,0xabb7,0x25d0,0x92d0,0xcab5,
        0xa950,0xb4a0,0xbaa4,0xad50,0x55d9,0x4ba0,0xa5b0,0x5176,0x52bf,0xa930,
        0x7954,0x6aa0,0xad50,0x5b52,0x4b60,0xa6e6,0xa4e0,0xd260,0xea65,0xd530,
        0x5aa0,0x76a3,0x96d0,0x4afb,0x4ad0,0xa4d0,0xd0b6,0xd25f,0xd520,0xdd45,
        0xb5a0,0x56d0,0x55b2,0x49b0,0xa577,0xa4b0,0xaa50,0xb255,0x6d2f,0xada0,
        0x4b63,0x937f,0x49f8,0x4970,0x64b0,0x68a6,0xea5f,0x6b20,0xa6c4,0xaaef,
        0x92e0,0xd2e3,0xc960,0xd557,0xd4a0,0xda50,0x5d55,0x56a0,0xa6d0,0x55d4,
        0x52d0,0xa9b8,0xa950,0xb4a0,0xb6a6,0xad50,0x55a0,0xaba4,0xa5b0,0x52b0,
        0xb273,0x6930,0x7337,0x6aa0,0xad50,0x4b55,0x4b6f,0xa570,0x54e4,0xd260,
        0xe968,0xd520,0xdaa0,0x6aa6,0x56df,0x4ae0,0xa9d4,0xa4d0,0xd150,0xf252,
        0xd520];
  private $lunarHolidayList=[];
  private $solarHolidayList=[];
	private $solarTerm=["小寒","大寒","立春","雨水","驚蟄","春分","清明","穀雨","立夏","小滿","芒種","夏至","小暑","大暑","立秋","處暑","白露","秋分","寒露","霜降","立冬","小雪","大雪","冬至"];
  private $monthNames=array(1=>"January",2=>"February",3=>"March",4=>"April",5=>"May",6=>"June",7=>"July",8=>"August",9=>"September",10=>"October",11=>"November",12=>"December");
  public $weekDayNames=[];
	private $solarStartDate;

  public function __construct()
  {
    $this->lunarHolidayList["0101"]="大年初一";
    $this->lunarHolidayList["0102"]="年初二";
    $this->lunarHolidayList["0103"]="年初三";
    $this->lunarHolidayList["0408"]="佛誕";
    $this->lunarHolidayList["0505"]="端午節";
    $this->lunarHolidayList["0816"]="中秋節翌日";
    $this->lunarHolidayList["0909"]="重陽節";

    $this->solarHolidayList["0101"]="新曆新年";
    $this->solarHolidayList["0501"]="勞動節";
    $this->solarHolidayList["0701"]="香港特別行政區成立紀念日";
    $this->solarHolidayList["1001"]="國慶日";
    $this->solarHolidayList["1225"]="聖誕節";
    $this->solarHolidayList["1226"]="聖誕節翌日";

	  $this->weekDayNames[0]="Su";
	  $this->weekDayNames[1]="M";
	  $this->weekDayNames[2]="T";
	  $this->weekDayNames[3]="W";
	  $this->weekDayNames[4]="Th";
	  $this->weekDayNames[5]="F";
	  $this->weekDayNames[6]="S";

	  $this->solarStartDate=new DateTime('1900-01-31', new DateTimeZone('UTC'));
  }


  /**
	 * 傳回該年的復活節LocalDate物件(春分後第一次滿月週後的第一主日)<br>
	 * It returns a LocalDate object which devote the date of easter of given year
	 * @param y 年份
	 * @return 傳回該年復活節LocalDate物件
	 */
	public function getEasterDateByYear($y)
	{
		$lMlen;$term2=$this->sTerm($y,5); //取得春分日期
		$dayTerm2=new DateTime($y."-3-".$term2, new DateTimeZone('UTC'));//取得春分的國曆日期物件(春分一定出現在3月)

		$lDayTerm2 = $this->getLunarDate($y,3,$term2); //取得取得春分農曆

		if ($lDayTerm2->lunarDate<15)
		{
			$lMlen=15-$lDayTerm2->lunarDate;
		}
		else
		{
			if ($lDayTerm2->isLeap)
			{
				$lMlen=$this->leapDays($y);//農曆 y年閏月的天數
			}
			else
			{
				$lMlen=$this->lunarMonthDayCount($lDayTerm2->lunarYear,$lDayTerm2->lunarMonth);//農曆 y年m月的總天數
			}
			$lMlen=$lMlen-$lDayTerm2->lunarDate + 15;
		}
		$dayTerm2->modify('+'.$lMlen.' day');
		if ($dayTerm2->format('w')==0)
		{
			$dayTerm2->modify('+1 day');
		}
		while ($dayTerm2->format('w')!=0)
		{
			$dayTerm2->modify('+1 day');
		}
		return $dayTerm2;
	}

	/**
	 * 傳回農曆 y年閏哪個月 1-12 , 沒閏傳回 0
	 * @param y
	 * @return 傳回農曆 y年閏哪個月 1-12 , 沒閏傳回 0
	 *
	 */
	private function getLunarLeapMonth($y)
	{
		$lm = $this->lunarInfo[$y-1900] & 0xf;
		return($lm==0xf?0:$lm);
	}
	/**
	 * 傳入LocalDate物件, 傳回LunarDate物件<br>
	 * It returns a corresponding LunarDate object when a LocalDate object is given.
	 * @param inLocalDateObj LocalDate物件
	 * @return LunarDate物件<br>
	 * A corresponding LunarDate object when a LocalDate object is given.
	 */
  public function getLunarDate($year,$month,$date)
  {
    $result=new DateObj();
	  $dateObj = new DateTime($year.'-'.$month.'-'.$date, new DateTimeZone('UTC'));

	  $interval = $this->solarStartDate->diff($dateObj);
    $offset=$interval->format('%R%a');
	  $result->solarDate=$date;
	  $result->solarMonth=$month;
	  $result->solarYear=$year;
	  $result->dayOfWeek=$dateObj->format('w');
	  for($i=1900; $i<2100 && $offset>0; $i++)
	  {
      $temp=$this->lYearDays($i);
      $offset-=$temp;
	  }
	  if($offset<0)
	  {
      $offset+=$temp;
      $i--;
	  }
	  $result->lunarYear=$i;
	  $lunarLeapMonth=$this->getLunarLeapMonth($i);
	  $result->isLeap=false;

	  for($i=1; $i<13 && $offset>0; $i++)
	  {
      //閏月
      if($lunarLeapMonth>0 && $i==($lunarLeapMonth) && $result->isLeap==false)
      {
        --$i;
        $result->isLeap = true;
        $temp = $this->leapDays($result->lunarYear);
      }
      else
      {
        $temp = $this->lunarMonthDayCount($result->lunarYear, $i);
      }

      //解除閏月
      if($result->isLeap==true && $i==($lunarLeapMonth))
        $result->isLeap = false;

      $offset -= $temp;
	  }
	  if($offset==0 && $lunarLeapMonth>0 && $i==$lunarLeapMonth)
      if($result->isLeap)
      {
        $result->isLeap = false;
      }
      else
      {
        $result->isLeap = true;
        --$i;
      }

		if($offset<0)
		{
			$offset += $temp;
			--$i;
		}
		$result->lunarMonth=$i;
		$result->lunarDate=$offset+1;
		return $result;
    }

	/**
	 * 傳回整個月的日期資料物件<br>
	 * It returns a MonthlyCalendar object when a year and month parameter is provided.
	 * @param year 年份
	 * @param month 月份
	 * @return MonthlyCalendar object
	 */
	public function getMonthlyCalendar($year,$month)
	{
    $dateObjList =[];
		$startDateObj=new DateTime($year.'-'.$month.'-1', new DateTimeZone('UTC'));
		$endDate=$startDateObj->format('t');
		$lunarHolidayDates=[];

    $solarMonthPattern=sprintf("%02d", $month);

		for ($i=1;$i<=$endDate;$i++)
		{
			$result=$this->getLunarDate($year,$month,$i);

			$lunarPattern=sprintf("%02d",$result->lunarMonth,2).sprintf("%02d",$result->lunarDate);
			if (array_key_exists($lunarPattern,  $this->lunarHolidayList)){
				$lunarHolidayDates[$i]=$this->lunarHolidayList[$lunarPattern];
			}
			$dateObjList[$i]=$result;
		}
		foreach(array_keys($this->solarHolidayList) as $key){

			$pos = strpos($key, $solarMonthPattern);
			if (($pos!==false) && ($pos==0))
			{
				//echo $pos.",".$solarMonthPattern.",".$key.",".intval (substr($key,2))."<br>";
				$this->processHoliday($dateObjList, $this->solarHolidayList[$key],intval(substr($key,2)));
			}
		}
		//echo json_encode($lunarHolidayDates)."<br>";
		switch ($month)
		{
			case 1:
			case 2: $this->processLunarYearHoliday($dateObjList,$lunarHolidayDates);//處理農曆新年假期
					break;
			case 3:	$this->processEasterHoliday($dateObjList,$year,$month);//復活節只出現在3或4月
					break;
			case 4: $chingMingDate=$this->sTerm($year,($month-1)*2); //取得清明節日期
					//echo $chingMingDate.",".$this->solarTerm[($month-1)*2]."<br>";
					$this->processHoliday($dateObjList,$this->solarTerm[($month-1)*2]."節",$chingMingDate);
					$this->processEasterHoliday($dateObjList,$year,$month);//復活節只出現在3或4月
					break;
			default://處理其餘理農曆假期
					foreach(array_keys($lunarHolidayDates) as $key){
						$this->processHoliday($dateObjList,$lunarHolidayDates[$key],$key);
					}
    }
    $monthlyCalendar=new MonthlyCalendar();
    $monthlyCalendar->monthName=$this->monthNames[$month]." ".$year;
    $monthlyCalendar->dateObjList=$dateObjList;
    $monthlyCalendar->month=$month;
    $monthlyCalendar->year=$year;
    foreach(array_keys($dateObjList) as $key){
      $dateObj=$dateObjList[$key];
      if (($dateObj->dayOfWeek!=0) && ($dateObj->dayOfWeek!=6) && (!$dateObj->isPublicHoliday))
        $monthlyCalendar->noOfWorkingDay++;
    }
		return $monthlyCalendar;
	}

	/**
	 * 處理補假
	 * @param myCalendarList
	 * @param festivalInfo
	 * @param inDate
	 */
	private function holidayCompensation($dateObjList,$festivalInfo,$inDate)
	{
		$dateObj=$dateObjList[$inDate];
		if (($dateObj->dayOfWeek==0) || ($dateObj->isPublicHoliday))
		{
			$this->holidayCompensation($dateObjList,$festivalInfo,$inDate+1);
		}
		else
		{
			if (array_key_exists($inDate-1,$dateObjList))
			{
				$dateObj2=$dateObjList[$inDate-1];
				if ($dateObj2->dayOfWeek==0)
				{
					if (strrpos($festivalInfo,"補假"))
					{
						$this->setHoliday($dateObjList,$festivalInfo,$dateObj->solarDate);
					}
					else
					{
						$this->setHoliday($dateObjList,$festivalInfo."補假",$dateObj->solarDate);
					}
				}
				else
				{
					if (strpos($dateObj2->festivalInfo,"補假"))
					{
						$this->setHoliday($dateObjList,$dateObj2->festivalInfo,$dateObj2->solarDate);
					}
					else
					{
						$this->setHoliday($dateObjList,$dateObj2->festivalInfo."補假",$dateObj2->solarDate);
					}
					if (strrpos($festivalInfo,"補假"))
					{
						$this->setHoliday($dateObjList,$festivalInfo,$dateObj->solarDate);
					}
					else
					{
						$this->setHoliday($dateObjList,$festivalInfo."補假",$dateObj->solarDate);
					}
				}
			}
		}
	}

	/**
	 * 傳回農曆 y年閏月的天數
	 * @param y
	 * @return 傳回農曆 y年閏月的天數
	 *
	 */
	protected function leapDays($y)
	{
		if($this->getLunarLeapMonth($y)!=0)
		{
			return( ($this->lunarInfo[$y-1899]&0xf)==0xf? 30: 29);
		}
		else
			return(0);
	}
	/**
	 * 傳回農曆 y年m月的總天數
	 * @param y 年份
	 * @param m 月份
	 * @return 傳回農曆 y年m月的總天數
	 *
	 */
	protected function lunarMonthDayCount($y,$m)
	{
		if (($this->lunarInfo[$y-1900] & (0x10000>>$m))>0)
			return 30;
		else
			return 29;
	}
	/**
	 *  傳回農曆 y年的總天數
	 *  @param y
	 *  @return 傳回農曆 y年的總天數
	 *
	 */
	private function lYearDays($y)
	{
		$sum = 348;
		for($i=0x8000; $i>0x8; $i>>=1)
		{
			if (($this->lunarInfo[$y-1900] & $i)>0)
			{
				$sum+=1;
			}
		}
		return($sum+$this->leapDays($y));
	}
	/**
	 * 處理復活假期
	 * @param myCalendarList
	 * @param festivalInfo
	 * @param inDate
	 */
	private function processEasterHoliday($dateObjList,$year,$month)
	{
		$easterDate=$this->getEasterDateByYear($year);

		$goodFriday=clone $easterDate;
		$holySaturday=clone $easterDate;
		$easterMonday=clone $easterDate;
		$goodFriday->modify("-2 day");
		$holySaturday->modify("-1 day");
		$easterMonday->modify("+1 day");

		if ($goodFriday->format("m")==$month)
			$this->processHoliday($dateObjList,"Good Friday",intval($goodFriday->format("d")));

		if ($holySaturday->format("m")==$month)
			$this->processHoliday($dateObjList,"Holy Saturday",intval($holySaturday->format("d")));

		if ($easterDate->format("m")==$month)
			$this->setFestivalInfo($dateObjList,"Easter",intval($easterDate->format("d")));

		if ($easterMonday->format("m")==$month)
			$this->processHoliday($dateObjList,"Easter Monday",intval($easterMonday->format("d")));
	}
	/**
	 * 處理農曆假期
	 * @param myCalendarList
	 * @param lunarHolidayDates
	 */
	private function processLunarYearHoliday($dateObjList,$lunarHolidayDates)
	{
		$maxDate=0;$i=0;
		$festivalInfo="";
		if (count($lunarHolidayDates)>0)
		{
			foreach(array_keys($lunarHolidayDates) as $key)
			{
				$dateObj=$dateObjList[$key];
				if ($dateObj->solarDate>$maxDate)
					$maxDate=$dateObj->solarDate;
				if($dateObj->dayOfWeek==0) //i.e. Sunday
				{
					$i++;
				}
				else
					$this->setHoliday($dateObjList,$lunarHolidayDates[$key],$key);
      }
      /*
      echo json_encode($lunarHolidayDates);
      echo json_encode($maxDate);
      */

			if ($i>0)
			{
        $festivalInfo=substr($lunarHolidayDates[$maxDate],0,6);
        //echo $festivalInfo."<br>";
				for ($j=$maxDate+1;$j<=$maxDate+$i;$j++)
				{
					if (array_key_exists($j,$dateObjList))
					{
						$dateObj=$dateObjList[$j];
						$this->setHoliday($dateObjList,$festivalInfo,$j);
					}
					else
						break;
				}
			}
		}
	}

	/**
	 * 處理假期
	 * @param myCalendarList
	 * @param festivalInfo
	 * @param inDate
	 */
	private function processHoliday($dateObjList,$festivalInfo,$inDate)
	{
		$dateObj=$dateObjList[$inDate];

		if (($dateObj->dayOfWeek!=0)&& (!$dateObj->isPublicHoliday))
		{
			//echo "jj".json_encode($dateObj)."<br>";
			$this->setHoliday($dateObjList,$festivalInfo,$inDate);
		}
		else
		{
			//echo "qq".json_encode($dateObj)."<br>";
			$this->holidayCompensation($dateObjList,$festivalInfo,$inDate);
		}
	}

	/**
	 * 設定當日的節日/假期資訊
	 * @param myCalendarList
	 * @param festivalInfo 節日/假期資訊
	 * @param date 當日的日期
	 */
	private function setFestivalInfo($dateObjList,$festivalInfo,$inDate)
	{
		$dateObj=$dateObjList[$inDate];
		$dateObj->isPublicHoliday=false;
		$dateObj->festivalInfo=$festivalInfo;
		$dateObjList[$inDate]=$dateObj;
	}

	/**
	 * 設定當日為假期
	 * @param myCalendarList
	 * @param festivalInfo 節日/假期資訊
	 * @param date 當日的日期
	 */
	private function setHoliday($dateObjList,$festivalInfo,$inDate)
	{
		$dateObj=$dateObjList[$inDate];

		$dateObj->isPublicHoliday=true;
		$dateObj->festivalInfo=$festivalInfo;
		$dateObjList[$inDate]=$dateObj;
		//echo "kk".json_encode($dateObj)."<hr>";
	}

	/**
	 * 傳回某年的第n個節氣為幾日(從0小寒起算)
	 * @param y 年份
	 * @param n 第幾個
	 * @return 某年的第n個節氣為幾日(從0小寒起算)
	 */
	public function sTerm($y,$n)
	{
		$result=0;
		$index;
		$temp;
		$solarTermBase=[4,19,3,18,4,19,4,19,4,20,4,20,6,22,6,22,6,22,7,22,6,21,6,21];
		$solarTermIdx ="0123415341536789:;<9:=<>:=1>?012@015@015@015AB78CDE8CD=1FD01GH01GH01IH01IJ0KLMN;LMBEOPDQRST0RUH0RVH0RWH0RWM0XYMNZ[MB\\]PT^_ST`_WH`_WH`_WM`_WM`aYMbc[Mde]Sfe]gfh_gih_Wih_WjhaWjka[jkl[jmn]ope]qph_qrh_sth_W";
		$solarTermOS = "211122112122112121222211221122122222212222222221222122222232222222222222222233223232223232222222322222112122112121222211222122222222222222222222322222112122112121222111211122122222212221222221221122122222222222222222222223222232222232222222222222112122112121122111211122122122212221222221221122122222222222222221211122112122212221222211222122222232222232222222222222112122112121111111222222112121112121111111222222111121112121111111211122112122112121122111222212111121111121111111111122112122112121122111211122112122212221222221222211111121111121111111222111111121111111111111111122112121112121111111222111111111111111111111111122111121112121111111221122122222212221222221222111011111111111111111111122111121111121111111211122112122112121122211221111011111101111111111111112111121111121111111211122112122112221222211221111011111101111111110111111111121111111111111111122112121112121122111111011111121111111111111111011111111112111111111111011111111111111111111221111011111101110111110111011011111111111111111221111011011101110111110111011011111101111111111211111001011101110111110110011011111101111111111211111001011001010111110110011011111101111111110211111001011001010111100110011011011101110111110211111001011001010011100110011001011101110111110211111001010001010011000100011001011001010111110111111001010001010011000111111111111111111111111100011001011001010111100111111001010001010000000111111000010000010000000100011001011001010011100110011001011001110111110100011001010001010011000110011001011001010111110111100000010000000000000000011001010001010011000111100000000000000000000000011001010001010000000111000000000000000000000000011001010000010000000";

		$solarTermIdxArray=array();
		$solarTermOSArray=array();

		for($i = 0; $i < strlen($solarTermIdx); $i++){
			$solarTermIdxArray[] = ord($solarTermIdx[$i]);
		}

		for($i = 0; $i < strlen($solarTermOS); $i++){
			$solarTermOSArray[] = ord($solarTermOS[$i]);
		}

		//return(solarTermBase[n] +  Math.floor( solarTermOS.charAt( ( Math.floor(solarTermIdx.charCodeAt(y-1900)) - 48) * 24 + n  ) ) );
		$index=$y-1900;
		$temp=$solarTermIdxArray[$index];
		$index=$temp;
		$index=($index-48)*24+$n;
		$result=$solarTermOSArray[$index]-48; //convert char to int
		$result+=$solarTermBase[$n];
		return $result;
	}
}
?>
