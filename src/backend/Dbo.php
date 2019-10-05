<?php
  class Dbo
	{
		private $conn;
		private $queryResult;
    private $sqlString;
    private $stmt;

		public function __construct()
		{
			try
			{
        $dbInfo =new DBInfo();
        $options = [
          PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
        ];
        $this->conn = new PDO('mysql:host='.$dbInfo->host.';dbname='.$dbInfo->database, $dbInfo->username, $dbInfo->password,$options);
				//$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //<-Enable throw SQL exception error
			}
			catch( PDOException $ex )
			{
				throw new Exception($ex);
			}
    }
    public function getITOList($year,$month) {
      $itoList=[];
      $startDateObj=new DateTime($year.'-'.$month.'-1', new DateTimeZone('UTC'));
      $endDateString=$startDateObj->format('Y-m-t');

      $this->sqlString ="SELECT join_date,leave_date,ito_info.ito_id,post_name,ito_name,available_shift,working_hour_per_day,black_list_pattern from ";
      $this->sqlString =$this->sqlString."ito_info inner join black_list_pattern ";
      $this->sqlString =$this->sqlString."on ito_info.ito_id=black_list_pattern.ito_id ";
      $this->sqlString =$this->sqlString."where join_date<=? and leave_date >=? ";
      $this->sqlString =$this->sqlString."order by ito_info.ito_id";
      $this->stmt=$this->conn->prepare($this->sqlString);
      $this->stmt->execute([$endDateString,$startDateObj->format('Y-m-d')]);
      while ($row = $this->stmt->fetch(PDO::FETCH_ASSOC)) {
        if (array_key_exists ( $row['ito_id'] , $itoList )){
          $ito=$itoList[$row['ito_id']];
          $ito->blackListedShiftPatternList[]=$row['black_list_pattern'];
          $itoList[$row['ito_id']]=$ito;
        }
        else {
          $ito=new ITO();
          $ito->itoId=$row['ito_id'];
          $ito->name=$row['ito_name'];
          $ito->postName=$row['post_name'];
          $ito->joinDate=$row['join_date'];
          $ito->leaveDate=$row['leave_date'];

          $ito->workingHourPerDay=$row['working_hour_per_day'];
          $ito->blackListedShiftPatternList[]=$row['black_list_pattern'];

          $tmp=explode( ',', $row['available_shift']);
          usort($tmp, 'strnatcasecmp');
          $ito->availableShiftList=$tmp;
          $itoList[$row['ito_id']]=$ito;
        }
      }
      return $itoList;
    }
    public function getITORosterList($year,$month, $itoList)
    {
      $result=[];
      $theMonthShiftStartDate=new DateTime($year.'-'.$month.'-1', new DateTimeZone('UTC'));
      $theMonthShiftEndDateString=$theMonthShiftStartDate->format('Y-m-t');
      foreach ($itoList as $itoId=>$ito) {
        $itoRoster=new ITORoster();
        $itoRoster->lastMonthBalance=0;
        $itoRoster->workingHourPerDay=$ito->workingHourPerDay;
        $itoRoster->itoId=$ito->itoId;
        $itoRoster->itoName=$ito->name;
        $itoRoster->itoPostName=$ito->postName;

        $this->sqlString ="select balance from last_month_balance where ito_Id=? and shift_month=?";
        $this->stmt=$this->conn->prepare($this->sqlString);
        $this->stmt->execute([$itoId,$theMonthShiftStartDate->format('Y-m-d')]);
        while ($row = $this->stmt->fetch(PDO::FETCH_ASSOC)) {
          $itoRoster->lastMonthBalance=$row['balance'];
        }

        $shiftList=[];
        $this->sqlString ="select day(shift_date) as d,shift from shift_record where ito_Id=? and (shift_record.shift_date between ? and ?)";
        $this->stmt=$this->conn->prepare($this->sqlString);
        $this->stmt->execute([$itoId,$theMonthShiftStartDate->format('Y-m-d'),$theMonthShiftEndDateString]);
        while ($row = $this->stmt->fetch(PDO::FETCH_ASSOC)) {
          $temp=$row["shift"];
          if (array_key_exists($row['d'],$shiftList)){
            $temp=$shiftList[$row["d"]]."+".$temp;
          }
          $shiftList[$row["d"]]=$temp;
        }
        for ($i=count($shiftList)+1;$i<32;$i++)
        {
          $shiftList[$i]="null";
        }
        $itoRoster->shiftList=$shiftList;
        $result[]=$itoRoster;
      }
      return $result;
    }
    public function getRosterRule()
    {
      $result=[];
      $ruleType="";
      $escapChar=chr(27);
      $keyValue=[];
      $this->sqlString ="select * from roster_rule order by rule_type,rule_key,rule_value";
      $this->stmt=$this->conn->prepare($this->sqlString);
      $this->stmt->execute();
      while ($row = $this->stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row["rule_type"] == $ruleType) {
          $keyValue[]=$row["rule_key"].$escapChar.$row["rule_value"];
        } else {
          if ($keyValue != null) {
            $result[$ruleType]=$keyValue;
          }
          $ruleType=$row["rule_type"];
          $keyValue=[];
          $keyValue[]=$row["rule_key"].$escapChar.$row["rule_value"];
        }
      }
      if ($keyValue != null) {
        $result[$ruleType]=$keyValue;
      }
      return $result;
    }
		public function close()
		{
			$this->conn =null;
		}
	}

?>
