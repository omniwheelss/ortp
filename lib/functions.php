<?php
	function date_diff_check($Date_Stamp,$Time_Stamp,$GMT_DRIFT){
		$last_date=GetUnixTimestamp($Date_Stamp,$Time_Stamp);		$gmtdate=GetUTCTimestamp()+($GMT_DRIFT*3600);		$difference=$gmtdate-$last_date;		$difference=round(($difference/3600),2);		$difference=abs($difference);		return $difference;	}

	//-------------------------------------------------------------------------------------------------------------
	// Function to get Unix time stamp from ASCII values of a given time and data
	//
	// Input Parameters:
	// $Date - Date in format dd.mm.yyyy
	// $Time - Time in format hh:mm:ss
	// Output Parameters
	// int - Unix timestamp

	function GetUnixTimestamp($Date, $Time){
	  $day   = substr($Date,0,2);
	  $month = substr($Date,3,2);
	  $year  = substr($Date,6,4);

	  $hour  = substr($Time,0,2);
	  $mins  = substr($Time,3,2);
	  $secs  = substr($Time,6,2);

	  $timestamp = gmmktime($hour,$mins,$secs,$month,$day,$year);
	  return $timestamp;
	}



	//-------------------------------------------------------------------------------------------------------------
	// Function to get the current UTC time stamp
	// No Input Parameters
	// Output Parameters
	// int - UTC Unix timestamp

	function GetUTCTimestamp (){
			$timestamp=time();
			return $timestamp;
	}

	//-------------------------------------------------------------------------------------------------------------
	// Function to compute geographical distance between a given set of co-ordinates.
	// function GetUnixTimestamp($Date, $Time)
	// Input Parameters:
	// $lat1 - Latitude of Co-ordinate 1
	// $lon1 - Longitude of Co-ordinate 1
	// $lat2 - Latitude of Co-ordinate 2
	// $lon2 - Longitude of Co-ordinate 2
	//
	// Output Parameters:
	// $d - Distance in Kms

	function distance($lat1, $lon1, $lat2, $lon2) {
		$iRadiusEarth = 6371; // kms
		$lat1 /= 57.29578;
		$lat2 /= 57.29578;
		$lon1 /= 57.29578;
		$lon2 /= 57.29578;

		$dlat=$lat2-$lat1;
		$dlon=$lon2-$lon1;

		$a = ( sin($dlat/2) * sin($dlat/2) ) + ( cos($lat1) * cos($lat2) ) * ( sin($dlon/2) * sin($dlon/2) );
		$c = 2 * atan2(sqrt($a), sqrt(1-$a));
		$d = $iRadiusEarth * $c;

		// Distance is returned in Kms
		return $d;
	}

	// ------------------------------------------------------------------------------------------------------------
	// Function to check the difference between 2 date/time stamps
	//
	// Input Parameters:
	// $date_1 - First Date in format dd.mm.yyyy
	// $time_1 - First Time in format hh:mm:ss
	// $date_2 - Second Date in format dd.mm.yyyy
	// $time_2 - Second Time in format hh:mm:ss
	//
	// Return values
	// $time_difference - Difference in time in seconds


	function timediff($date_1,$time_1,$date_2,$time_2){
			$x=GetUnixTimestamp($date_1,$time_1);
			$y=GetUnixTimestamp($date_2,$time_2);
			$time_difference=$y-$x;
			return $time_difference;	}


	// ------------------------------------------------------------------------------------------------------------
	// Function to convert date display based on account defaults based for an IMEI
	//
	// Input Parameters:
	// IMEI  - IMEI for which date format has to be changed
	// $Date - Date in format dd.mm.yyyy
	//
	// Output Parameters
	// Date in either ddmmyyyy or mmddyyyy format with the defined date separator

	function convert_date_display($date)
	{
	  global $DATE_FORMAT;
	  global $DATE_SEPERATOR;

	  if( $DATE_FORMAT == 1 )
	  {
		$dd = substr($date,0,2);
		$mm = substr($date,3,2);
		$yy = substr($date,6,4);
		return "$mm$DATE_SEPERATOR$dd$DATE_SEPERATOR$yy";
	  }
	  else
	  {
		$dd = substr($date,0,2);
		$mm = substr($date,3,2);
		$yy = substr($date,6,4);
		return "$dd$DATE_SEPERATOR$mm$DATE_SEPERATOR$yy";
	  }
	}



	function get_time_difference( $start, $end ){

		$uts['start']      =    strtotime( $start );
		$uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{
			if( $uts['end'] >= $uts['start'] )
			{
				$diff    =    $uts['end'] - $uts['start'];
				if( $days=intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff    =    intval( $diff );            
				return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
			}
			else
			{
				trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
			}
		}
		else
		{
			trigger_error( "Invalid date/time data detected", E_USER_WARNING );
		}
		return( false );
	}	#####################################################	#	#	Epoch Time Difference	#	########################################################	function Epoch_Diff($Date_Stamp){				$Output= null;		$Date_Stamp1_Epoch = strtotime($Date_Stamp);		$Date_Stamp2_Epoch = time();		$Output = $Date_Stamp2_Epoch - $Date_Stamp1_Epoch;		return $Output;	}		
	#####################################################	#	#	Device List	#	########################################################	function Device_List($User_Account_ID){				$Device_List_Array = null;		$Mysql_Query = "select * from device_master where user_account_id = '".$User_Account_ID."'";		$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());		$device_count = mysql_num_rows($Mysql_Query_Result);		if($device_count>=1){			while($device_list = mysql_fetch_array($Mysql_Query_Result)){				$Device_List_Array[] = $device_list;			}		}		return $Device_List_Array;	}	#####################################################	#	#	Device Status	#	########################################################	function Device_Status($IMEI){				$Device_Status_Array = null;		$Device_Time_Diff = null;				$Mysql_Query = "select * from device_data where IMEI = '".$IMEI."' order by device_date_stamp desc limit 1";		$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());		$device_count = mysql_num_rows($Mysql_Query_Result);		if($device_count == 1){			$Device_Status_Array = mysql_fetch_array($Mysql_Query_Result);			$IMEI = $Device_Status_Array['imei'];			$Device_Status_Array['device_status'] = null;			$Alert_Msg_Code = explode("|", $Device_Status_Array['alert_msg_code']);						$Current_Date = date("d-m-Y");			$Current_Time = date("H:i:s");			$Device_Date[$imei] = date("d-m-Y",strtotime($IMEI));			$Device_Time[$imei] = date("H:i:s",strtotime($IMEI));			//echo "<br />".$Device_Status_Array['device_date_stamp']."----";			// Get difference 			$Device_Epoch_Diff = Epoch_Diff($Device_Status_Array['device_date_stamp']);						// Unknown Status			if($Device_Epoch_Diff >= 1800){				$Device_Status_Array['device_status'] = "Unknown";				$Device_Status_Array['ign'] = "Unknown";				$Device_Status_Array['speed'] = "NA";				$Device_Status_Array['location'] = "NA";				$Device_Status_Array['status_icon'] = "grey.png";			}			// Moving Status			else if($Device_Status_Array['live_data'] == 1 && $Device_Status_Array['speed'] > 10 && $Device_Status_Array['ign'] == 1 ){				$Device_Status_Array['device_status'] = "Moving";				$Device_Status_Array['ign'] = "On";				$Device_Status_Array['status_icon'] = "green.png";			}			// Stopped Status			else if($Device_Status_Array['live_data'] == 1 && $Device_Status_Array['speed'] == 0 && $Device_Status_Array['ign'] == 0){				$Device_Status_Array['device_status'] = "Stopped";				$Device_Status_Array['ign'] = "Off";				$Device_Status_Array['status_icon'] = "red.png";			}			// Idle Status			else if(($Device_Status_Array['live_data'] == 1 && $Device_Status_Array['speed'] <= 10 && $Device_Status_Array['ign'] == 1) || $Alert_Msg_Code[0] == 'VI'){				$Device_Status_Array['device_status'] = "Idle";				$Device_Status_Array['ign'] = "On";				$Device_Status_Array['speed'] = 0;				$Device_Status_Array['status_icon'] = "orange.png";			}								}		return $Device_Status_Array;			}	#####################################################	#	#	Device Status	#	########################################################	function Device_Info($IMEI){				$Device_Info = null;		$Mysql_Query = "select * from device_master where imei = '".$IMEI."'";		$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());		$Query_Count = mysql_num_rows($Mysql_Query_Result);		if($Query_Count>=1){			while($Query_List = mysql_fetch_array($Mysql_Query_Result)){				$Device_Info[$IMEI] = $Query_List;			}		}		return $Device_Info;	}				#####################################################	#	#	Device Status	#	########################################################	function Geofence_List($IMEI, $From_Date, $To_Date){				$Geofence_Array = null;		//$Mysql_Query = "select * from geo_fence_alerts where imei = '".$IMEI."' and date_stamp between '".$From_Date."' and '".$To_Date."' order by id asc limit 4345453454 OFFSET 1";		$Mysql_Query = "select * from geo_fence_alerts where imei = '".$IMEI."' and date_stamp between '".$From_Date."' and '".$To_Date."' order by date_stamp asc";		$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());		$Query_Count = mysql_num_rows($Mysql_Query_Result);		$c = $d = 1;		if($Query_Count>=1){						while($Result = mysql_fetch_array($Mysql_Query_Result)){								$Date_Stamp = 	$Result['date_stamp'];				$Trip_Status = 	$Result['status'];				$Trip_Index = 	$Result['trip_index'];				$Latitude = 	$Result['latitude'];
				$Longitude = 	$Result['longitude'];
				
				// Skip if first record is "IN"
				if(!($c == 1 && $Trip_Status == "IN")){
										if($c%2 == 0){								$Geofence_Array[$Previous_d]['in_date_stamp'] = $Date_Stamp;						$Geofence_Array[$Previous_d]['in_status'] = $Trip_Status;						$Geofence_Array[$Previous_d]['in_trip_index'] = $Trip_Index;						$Geofence_Array[$Previous_d]['in_latitude'] = $Latitude;
						$Geofence_Array[$Previous_d]['in_longitude'] = $Longitude;
						$d++;					}					else{						$Geofence_Array[$d]['out_date_stamp'] = $Date_Stamp;						$Geofence_Array[$d]['out_status'] = $Trip_Status;						$Geofence_Array[$d]['out_trip_index'] = $Trip_Index;						$Geofence_Array[$d]['out_latitude'] = $Latitude;
						$Geofence_Array[$d]['out_longitude'] = $Longitude;
						$Previous_d = $d;					}						
					$c++;				}	
			}		}		return $Geofence_Array;	}			#####################################################	#	#	Geofence	#	########################################################	function Geofence_Details($id){				$Query_List_Array = null;		$Mysql_Query = "select * from geo_fence where id = '".$id."'";		$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());		$Query_Count = mysql_num_rows($Mysql_Query_Result);		if($Query_Count>=1){			$Query_List = mysql_fetch_array($Mysql_Query_Result);			$Query_List_Array = $Query_List;		}		return $Query_List_Array;	}		#####################################################	#	#	Geofence	#	########################################################	function Dates_POI(){				$Date_List_Array = array();		$Month = 10;		$Year = date("Y");		for($d=6; $d <= 31; $d++)		{			$Time=mktime(12, 0, 0, $Month, $d, $Year);          			if (date('m', $Time)==$Month){				$Date = date('Y-m-d', $Time);				$Date_List_Array[$Date]=date('Y-m-d - D', $Time);			}			}						$Month = date("m");		for($d=1; $d <= date("d"); $d++)		{			$Time=mktime(12, 0, 0, $Month, $d, $Year);          			if (date('m', $Time)==$Month){				$Date = date('Y-m-d', $Time);				$Date_List_Array1[$Date]=date('Y-m-d - D', $Time);			}			}				$Final_Array = array_merge($Date_List_Array, $Date_List_Array1);		return $Final_Array;	}	#####################################################	#	#	Change password	#	########################################################		function Change_Password($Old_Pass, $New_pass, $User_Account_ID){			$Result = null;		$Old_Pass = md5($_REQUEST['Old_Pass']);		$Pass = $_REQUEST['Pass'];				$Change_Password_Sql = "select * from user_master where Password = '".$Old_Pass."' and user_account_id = '".$User_Account_ID."'";		$Change_Password_Run = mysql_query($Change_Password_Sql) or die(mysql_error());		$Change_Password_Count = mysql_num_rows($Change_Password_Run); 		if($Change_Password_Count == 1){			$Change_Pass_Sql = "update user_master set Password = '".md5($New_pass)."' where user_account_id = '".$User_Account_ID."'";			$Change_Pass_Run = mysql_query($Change_Pass_Sql) or die(mysql_error());			$Result = "Success";				}		else{			$Result = "Failure";		}				return $Result;		}	######################################	#	#	Get Epoch Difference	#	############################################	function Get_EpochDiff($Epoch1,$Epoch2){				$Result = null;		if(!empty($Epoch1) && !empty($Epoch2)){				$Result = $Epoch2 - $Epoch1;		}		return $Result;	}	###############################################	#	#	Get Epoch Difference for Location Summary	#	###############################################	function Get_EpochDiff_Vehicle($Epoch1,$Epoch2, $Previous_Status, $Current_Status, $Diff_Record){		$Result = null;		if(!empty($Epoch1) && !empty($Epoch2)){						// Result			$Result = $Epoch2 - $Epoch1;						if($Diff_Record == 0){								if($Current_Status == 'Moving'){					if($Result > 60)						$Result = 60;				}				else if($Current_Status == 'Stopped'){					if($Result > 300)						$Result = 300;				}				else if($Current_Status == 'Idle'){					if($Result > 60)						$Result = 60;				}			}			else if ($Diff_Record == 1){								if($Previous_Status == 'Stopped' && $Current_Status == 'Idle' || $Previous_Status == 'Idle' && $Current_Status == 'Stopped'  || $Previous_Status == 'Moving' && $Current_Status == 'Stopped' || $Previous_Status == 'Stopped' && $Current_Status == 'Moving'){					if($Result > 300)						$Result = 300;				}				else if($Previous_Status == 'Idle' && $Current_Status == 'Moving' || $Previous_Status == 'Moving' && $Current_Status == 'Idle'){					if($Result > 60)						$Result = 60;				}			}					}		return $Result;	}	######################################	#	#       Date Difference	#	############################################	function Epoch_To_Time($Epoch)	{		$time = $Epoch;				if($time>=0 && $time<=59) {			// Seconds			//$timeshift = $time.' seconds ';			if($premin[0] > 0 || $time > 0)			$timeshift = $preday[0].' : '.$prehour[0].' : '.$premin[0].' min '.$time.' sec ';			//$timeshift = '<table border="0" cellpadding="0" cellspacing="0" width="100px" class="time_tab"><tr><td width="25px;">'.$preday[0].'</td><td>'.$prehour[0].' : '.$min[0].'</td></tr></table>';		} elseif($time>=60 && $time<=3599) {			// Minutes + Seconds			$pmin = $time / 60;			$premin = explode('.', $pmin);			$presec = $pmin-$premin[0];			$sec = $presec*60;			$timeshift = $premin[0].' min '.round($sec,0).' sec ';			//$timeshift = '<table border="0" cellpadding="0" cellspacing="0" width="100px" class="time_tab"><tr><td width="25px;">'.$preday[0].'</td><td>'.$prehour[0].' : '.$min[0].'</td></tr></table>';		} elseif($time>=3600 && $time<=86399) {			// Hours + Minutes			$phour = $time / 3600;			$prehour = explode('.',$phour);			$premin = $phour-$prehour[0];			$min = explode('.',$premin*60);			$presec = '0.'.$min[1];			$sec = $presec*60;			$timeshift = $prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec ';			//$timeshift = '<table border="0" cellpadding="0" cellspacing="0" width="100px" class="time_tab"><tr><td width="25px;">'.$preday[0].'</td><td>'.$prehour[0].' : '.$min[0].'</td></tr></table>';		} elseif($time>=86400) {			// Days + Hours + Minutes			$pday = $time / 86400;		   $preday = explode('.',$pday);			$phour = $pday-$preday[0];			$prehour = explode('.',$phour*24);			$premin = ($phour*24)-$prehour[0];			$min = explode('.',$premin*60);			$presec = '0.'.$min[1];			$sec = $presec*60;			$timeshift = $preday[0].' days '.$prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec ';			//$timeshift = '<table border="0" cellpadding="0" cellspacing="0" width="100px" class="time_tab"><tr><td width="25px;">'.$preday[0].'</td><td>'.$prehour[0].' : '.$min[0].'</td></tr></table>';		}		return $timeshift;	}			######################################	#	#       Date Difference	#	############################################	function datetime_diff($start, $end)	{		$sdate = strtotime($start);		$edate = strtotime($end);		$time = $edate - $sdate;		if($time>=0 && $time<=59) {			// Seconds			//$timeshift = $time.' seconds ';							//$timeshift = $preday[0].' : '.$prehour[0].' : '.$premin[0].' '.$time.' seconds ';			$timeshift = '<table border="0" cellpadding="0" cellspacing="0" width="100px" class="time_tab"><tr><td width="25px;">'.$preday[0].'</td><td>'.$prehour[0].' : '.$min[0].'</td></tr></table>';		} elseif($time>=60 && $time<=3599) {			// Minutes + Seconds			$pmin = ($edate - $sdate) / 60;			$premin = explode('.', $pmin);			$presec = $pmin-$premin[0];			$sec = $presec*60;			//$timeshift = $premin[0].' min '.round($sec,0).' sec ';						$timeshift = '<table border="0" cellpadding="0" cellspacing="0" width="100px" class="time_tab"><tr><td width="25px;">'.$preday[0].'</td><td>'.$prehour[0].' : '.$min[0].'</td></tr></table>';		} elseif($time>=3600 && $time<=86399) {			// Hours + Minutes			$phour = ($edate - $sdate) / 3600;			$prehour = explode('.',$phour);			$premin = $phour-$prehour[0];			$min = explode('.',$premin*60);			$presec = '0.'.$min[1];			$sec = $presec*60;			//$timeshift = $prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec ';						$timeshift = '<table border="0" cellpadding="0" cellspacing="0" width="100px" class="time_tab"><tr><td width="25px;">'.$preday[0].'</td><td>'.$prehour[0].' : '.$min[0].'</td></tr></table>';		} elseif($time>=86400) {			// Days + Hours + Minutes			$pday = ($edate - $sdate) / 86400;		   $preday = explode('.',$pday);			$phour = $pday-$preday[0];			$prehour = explode('.',$phour*24);			$premin = ($phour*24)-$prehour[0];			$min = explode('.',$premin*60);			$presec = '0.'.$min[1];			$sec = $presec*60;		   // $timeshift = $preday[0].' days '.$prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec ';			$timeshift = '<table border="0" cellpadding="0" cellspacing="0" width="100px" class="time_tab"><tr><td width="25px;">'.$preday[0].'</td><td>'.$prehour[0].' : '.$min[0].'</td></tr></table>';		}		return $timeshift;	}			######################################	#	#      calculate_average Speed	#	############################################	function Calculate_Average($Data_Array) {		$Array_Count = count($Data_Array); //total numbers in array		$Average = 0;				foreach ($Array_Count as $Value) {			$Total = $Total + $Value; // total value of array numbers		}		$Average = (array_sum($Data_Array)/$Array_Count); // get average value		return $Average;	}		######################################	#	#       Difference between records	#	############################################		function Diff_Between_Records($Type, $Get_Array, $Previous_Status, $Current_Status, $Diff_Record){		$Result = null;		$Array_Count = count($Get_Array); 		if($Array_Count > 0){			$I = 0;			// Difference_between_Time			foreach($Get_Array as $Get_Val){								// Skip the last record since we added +1 to second Get_Val record				if($I != ($Array_Count-1))				{					if($Type == 'time'){						$Result[] = Get_TimeDiff($Get_Array[$I],$Get_Array[$I+1]);					}					else if ($Type == 'epoch'){						$Result[] = Get_EpochDiff_Vehicle($Get_Array[$I],$Get_Array[$I+1], $Previous_Status, $Current_Status, $Diff_Record);					}				}				$I++;			}		}			else{			$Result = null;		}		return $Result;	}			############################################	#	#    Vehicle Data Current Status	#	############################################			function Data_Current_Status($GPS_Move_Status, $Speed, $IGN, $Alert_Msg_Code){		$Alert_Msg_Code = explode("|",$Alert_Msg_Code);		$Result = null;		// Moving Status		if($Speed > 10 && $IGN == 1){			$Status = "Moving";			$IGN = "On";			$Status_Icon = "green.png";		}		// Stopped Status		else if($Speed == 0 && $IGN == 0){			$Status = "Stopped";			$IGN = "Off";			$Status_Icon = "red.png";		}		// Idle Status		else if(($Speed <= 10 && $IGN == 1) || $Alert_Msg_Code[0] == 'VI'){			$Status = "Idle";			$IGN = "On";			//$Speed = 0;			$Status_Icon = "orange.png";		}		return $Result = array($Status, $IGN, $Status_Icon);		}				############################################	#	#    Decision_Maker_Pocket_Diff	#	############################################		function Decision_Maker_Pocket_Diff($Data_Pre_Status_Val, $Data_Cur_Status_Val, $Pre_Cur_Diff_Sum){				$Result = null;		$Moving_Text = "Diff Btwn above and below Record : Moving--".Epoch_To_Time($Pre_Cur_Diff_Sum)."<br />";		$Stopped_Text = "Diff Btwn above and below Record : Stopped--".Epoch_To_Time($Pre_Cur_Diff_Sum)."<br />";		$Idle_Text = "Diff Btwn above and below Record : Idle--".Epoch_To_Time($Pre_Cur_Diff_Sum)."<br />";		$Unknown_Text = "Diff Btwn above and below Record : Unknown--".Epoch_To_Time($Pre_Cur_Diff_Sum)."<br />";				// For Moving		if($Data_Pre_Status_Val == 'Moving' && $Data_Cur_Status_Val == 'Idle' && $Pre_Cur_Diff_Sum <= 60){			$Moving_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Moving";			//echo $Moving_Text;		}		else if($Data_Pre_Status_Val == 'Moving' && $Data_Cur_Status_Val == 'Idle' && $Pre_Cur_Diff_Sum > 60){			$Idle_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Idle";			//echo $Idle_Text;		}		else if($Data_Pre_Status_Val == 'Moving' && $Data_Cur_Status_Val == 'Stopped' && $Pre_Cur_Diff_Sum <= 60){			$Stopped_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Stopped";			//echo $Stopped_Text;		}		else if($Data_Pre_Status_Val == 'Moving' && $Data_Cur_Status_Val == 'Stopped' && $Pre_Cur_Diff_Sum > 60){			$Stopped_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Stopped";			//echo $Stopped_Text;		}				// For Stopped		else if($Data_Pre_Status_Val == 'Stopped' && $Data_Cur_Status_Val == 'Moving' && $Pre_Cur_Diff_Sum <= 60){			$Moving_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Moving";			//echo $Moving_Text;		}		else if($Data_Pre_Status_Val == 'Stopped' && $Data_Cur_Status_Val == 'Moving' && $Pre_Cur_Diff_Sum > 60){			$Stopped_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Stopped";			//echo $Stopped_Text;		}		else if($Data_Pre_Status_Val == 'Stopped' && $Data_Cur_Status_Val == 'Idle' && $Pre_Cur_Diff_Sum <= 60){			$Idle_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Idle";			//echo $Idle_Text;		}		else if($Data_Pre_Status_Val == 'Stopped' && $Data_Cur_Status_Val == 'Idle' && $Pre_Cur_Diff_Sum > 60){			$Stopped_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Stopped";			//echo $Stopped_Text;		}				// For Idle		else if($Data_Pre_Status_Val == 'Idle' && $Data_Cur_Status_Val == 'Moving' && $Pre_Cur_Diff_Sum <= 60){			$Moving_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Moving";			//echo $Moving_Text;		}		else if($Data_Pre_Status_Val == 'Idle' && $Data_Cur_Status_Val == 'Moving' && $Pre_Cur_Diff_Sum > 60){			$Idle_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Idle";			//echo $Idle_Text;		}		else if($Data_Pre_Status_Val == 'Idle' && $Data_Cur_Status_Val == 'Stopped' && $Pre_Cur_Diff_Sum <= 60){			$Idle_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Idle";			//echo $Idle_Text;		}		else if($Data_Pre_Status_Val == 'Idle' && $Data_Cur_Status_Val == 'Stopped' && $Pre_Cur_Diff_Sum > 60){			$Stopped_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Stopped";			//echo $Stopped_Text;		}		else{			$Unknown_Additional_Diff = $Pre_Cur_Diff_Sum;			$Maker_Decision = "Unknown";			//echo $Unknown_Text;		}		$Result = array($Moving_Additional_Diff, $Stopped_Additional_Diff, $Idle_Additional_Diff, $Unknown_Additional_Diff, $Maker_Decision);				return $Result;	}			############################################	#	#    Vehicle Data Current Status	#	############################################	function Remove_Invalid_Records($Result_Array){				$Final_Array = null;		$Alert_Msg_Code = $Result_Array['alert_msg_code'];		$Live_Data = $Result_Array['live_data'];		$GPS_Status = $Result_Array['gps_status'];		$Speed = $Result_Array['speed'];		$GPS_Move_Status = $Result_Array['sps_move_status'];		$IGN = $Result_Array['ign'];				// Skip idle status		if($GPS_Status  == 1 && $Speed <= 10 && $GPS_Move_Status == 2 && $IGN == 1){			print_r("SEENI".$Result_Array);			//$Final_Array = unset($Result_Array[0]);		}		else{			$Final_Array = $Result_Array;		}					return $Final_Array;			}			############################################	#	#    Add_Vehicle_Status_Diff_AddDiff	#	############################################	function Add_Vehicle_Status_Diff_AddDiff($Speed_Array, $All_DateTime_Diff, $All_DateTime_NE_Diff, $DateTime_Moving_Diff, $DateTime_Stopped_Diff, $DateTime_Idle_Diff, 		$DateTime_Unknown_Diff, $Decision_Maker_Moving_Diff, $Decision_Maker_Stopped_Diff, $Decision_Maker_Idle_Diff, $Decision_Maker_Unknown_Diff,$Total_KM_Value ){			// Data for all		$All_DateTime_Diff = array_sum($All_DateTime_Diff) + array_sum($All_DateTime_NE_Diff);		$Total_Pocket_Time = Epoch_To_Time($All_DateTime_Diff);		// Data for Moving		$DateTime_Moving_Diff = array_sum($DateTime_Moving_Diff) + array_sum($Decision_Maker_Moving_Diff);		$Total_Moving_Pocket_Time = Epoch_To_Time($DateTime_Moving_Diff);				// Data for Stopped		$DateTime_Stopped_Diff = array_sum($DateTime_Stopped_Diff) + array_sum($Decision_Maker_Stopped_Diff);		$Total_Stopped_Pocket_Time = Epoch_To_Time($DateTime_Stopped_Diff);				// Data for Idle		$DateTime_Idle_Diff = array_sum($DateTime_Idle_Diff) + array_sum($Decision_Maker_Idle_Diff);		$Total_Idle_Pocket_Time = Epoch_To_Time($DateTime_Idle_Diff);				// Data for Unknown		$DateTime_Unknown_Diff = array_sum($DateTime_Unknown_Diff) + array_sum($Decision_Maker_Unknown_Diff);		$Total_Unknown_Pocket_Time = Epoch_To_Time($DateTime_Unknown_Diff);				$Total_Seperated_Time = $DateTime_Moving_Diff + $DateTime_Stopped_Diff + $DateTime_Idle_Diff + $DateTime_Unknown_Diff;		$Total_Seperated_Time = Epoch_To_Time($Total_Seperated_Time);		/*		echo "<hr /><h4>Total Up Time -- ".$Total_Pocket_Time;		echo "<br />Total Seperated Up Time -- ".Epoch_To_Time($Total_Seperated_Time);		echo "</h4><br />Moving Time -- ".$Total_Moving_Pocket_Time;		echo "<br />Stopped Time -- ".$Total_Stopped_Pocket_Time;		echo "<br />Idle Time -- ".$Total_Idle_Pocket_Time;		echo "<br />Unknown Time -- ".$Total_Unknown_Pocket_Time;		echo "<hr />";		echo "<br />Diff Time -- ".Epoch_To_Time(array_sum($All_DateTime_NE_Diff));		echo "<br />Diff Moving Time -- ".Epoch_To_Time(array_sum($Decision_Maker_Moving_Diff));		echo "<br />Diff Stopped Time -- ".Epoch_To_Time(array_sum($Decision_Maker_Stopped_Diff));		echo "<br />Diff Idle Time -- ".Epoch_To_Time(array_sum($Decision_Maker_Idle_Diff));		echo "<br />Diff Unknown Time -- ".Epoch_To_Time(array_sum($Decision_Maker_Unknown_Diff));		*/		return array($Speed_Array, $Total_Pocket_Time, $Total_Seperated_Time, $Total_Moving_Pocket_Time, $Total_Stopped_Pocket_Time, $Total_Idle_Pocket_Time, $Total_Unknown_Pocket_Time, $Total_KM_Value);	}		############################################	#	#   Get_Daily_Summary	#	############################################		function Get_Daily_Summary($Date, $IMEI){				$Result = null;				$From_Date = $Date. " 00:00:00";		$To_Date = $Date. " 23:59:59";		$Mysql_Query = "select * from device_data where imei = '".$IMEI."' and device_date_stamp between '".$From_Date."' and '".$To_Date."' and alert_msg_code != 'IN|0' order by device_date_stamp asc";		$Mysql_Query_Result = mysql_query($Mysql_Query) or die(mysql_error());		$Row_Count = mysql_num_rows($Mysql_Query_Result);		if($Row_Count >=1){			$i = 1;			$Decision_Maker_All_Diff = array();			$Decision_Maker_Moving_Diff = array();			$Decision_Maker_Stopped_Diff = array();			$Decision_Maker_Idle_Diff = array();			$Decision_Maker_Unknown_Diff = array();						while($Result_Array = mysql_fetch_array($Mysql_Query_Result)){								// Skip invalid Records				//$Valid_Records = Remove_Invalid_Records($Result_Array);								//foreach($Valid_Records as $Result_Array)				{					$Diff_Record = 0;					$Speed_Array[] = $Result_Array['speed'];					$Device_Stamp_All_Array[] = $Result_Array['device_date_stamp'];					$GPS_Move_Status = $Result_Array['gps_move_status'];					$IGN = $Result_Array['ign'];					$Speed = $Result_Array['speed'];					$Alert_Msg_Code = $Result_Array['alert_msg_code'];										// Current Status Check					$Data_Cur_Status = Data_Current_Status($GPS_Move_Status, $Speed, $IGN, $Alert_Msg_Code);					$Data_Cur_Status_Val = $Data_Cur_Status[0];					$Data_Pre_Status_Val = $Data_Pre_Array[0];										// Checking Record is different and assign flag					if($Data_Pre_Status_Val != $Data_Cur_Status_Val && !empty($Data_Pre_Status_Val)){						$Diff_Record = 1;					}										$Pre_Cur_Diff_Array = array($Data_Pre_Array[1], $Result_Array['device_epoch_time']);					// Calucalte only equal record - not diff record					if($Diff_Record == 0){						// All data sequence						$Pre_Cur_Diff_Val = Diff_Between_Records('epoch', $Pre_Cur_Diff_Array, $Data_Pre_Status_Val, $Data_Cur_Status_Val, $Diff_Record);						$Pre_Cur_Diff_Sum = array_sum($Pre_Cur_Diff_Val);						$All_DateTime_Diff[] = $Pre_Cur_Diff_Sum;						// Data by status						// Moving						if($Data_Cur_Status_Val  == 'Moving'){							$Device_Stamp_Moving_Array[] = $Result_Array['device_epoch_time'];							$Result_Array['device_date_stamp'] = "Moving--".$Result_Array['device_date_stamp'];							$Pre_Cur_Diff_Moving_Val = Diff_Between_Records('epoch', $Pre_Cur_Diff_Array, $Data_Pre_Status_Val, $Data_Cur_Status_Val, $Diff_Record);							$Pre_Cur_Diff_Moving_Sum = array_sum($Pre_Cur_Diff_Moving_Val);							$DateTime_Moving_Diff[] = $Pre_Cur_Diff_Moving_Sum;						}						//Stopped						else if($Data_Cur_Status_Val == 'Stopped'){							$Device_Stamp_Stopped_Array[] = $Result_Array['device_epoch_time'];							$Result_Array['device_date_stamp'] = "Stopped--".$Result_Array['device_date_stamp'];							$Pre_Cur_Diff_Stopped_Val = Diff_Between_Records('epoch', $Pre_Cur_Diff_Array, $Data_Pre_Status_Val, $Data_Cur_Status_Val, $Diff_Record);							$Pre_Cur_Diff_Stopped_Sum = array_sum($Pre_Cur_Diff_Stopped_Val);							$DateTime_Stopped_Diff[] = $Pre_Cur_Diff_Stopped_Sum;						}						//Idle						else if($Data_Cur_Status_Val == 'Idle'){							$Device_Stamp_Idle_Array[] = $Result_Array['device_epoch_time'];							$Result_Array['device_date_stamp'] = "Idle--".$Result_Array['device_date_stamp'];							$Pre_Cur_Diff_Idle_Val = Diff_Between_Records('epoch', $Pre_Cur_Diff_Array, $Data_Pre_Status_Val, $Data_Cur_Status_Val, $Diff_Record);							$Pre_Cur_Diff_Idle_Sum = array_sum($Pre_Cur_Diff_Idle_Val);							$DateTime_Idle_Diff[] = $Pre_Cur_Diff_Idle_Sum;						}						//Unknown						else{							$Device_Stamp_Unknown_Array[] = $Result_Array['device_epoch_time'];							$Result_Array['device_date_stamp'] = "Unknown--".$Result_Array['device_date_stamp'];							$Pre_Cur_Diff_Unknown_Val = Diff_Between_Records('epoch', $Pre_Cur_Diff_Array, $Data_Pre_Status_Val, $Data_Cur_Status_Val, $Diff_Record);							$Pre_Cur_Diff_Unknown_Sum = array_sum($Pre_Cur_Diff_Unknown_Val);							$DateTime_Unknown_Diff[] = $Pre_Cur_Diff_Unknown_Sum;						}											}					else if($Diff_Record == 1){						// All data diff						$Pre_Cur_Diff_Val = Diff_Between_Records('epoch', $Pre_Cur_Diff_Array, $Data_Pre_Status_Val, $Data_Cur_Status_Val, $Diff_Record);						$Pre_Cur_Diff_Sum = array_sum($Pre_Cur_Diff_Val);						$All_DateTime_NE_Diff[] = $Pre_Cur_Diff_Sum;						// Decide whom to assign the difference 								$Decision_Maker_Pocket_Diff = Decision_Maker_Pocket_Diff($Data_Pre_Status_Val, $Data_Cur_Status_Val, $Pre_Cur_Diff_Sum);						$Maker_Decision = $Decision_Maker_Pocket_Diff[4];						if($Maker_Decision == 'Moving'){							array_push($Decision_Maker_Moving_Diff, $Decision_Maker_Pocket_Diff[0]);						}						else if($Maker_Decision == 'Stopped'){							array_push($Decision_Maker_Stopped_Diff, $Decision_Maker_Pocket_Diff[1]);						}						else if($Maker_Decision == 'Idle'){							array_push($Decision_Maker_Idle_Diff, $Decision_Maker_Pocket_Diff[2]);						}						else if($Maker_Decision == 'Unknown'){							array_push($Decision_Maker_Unknown_Diff, $Decision_Maker_Pocket_Diff[3]);						}												// Just for debug 						// Moving						if($Data_Cur_Status_Val  == 'Moving'){							$Result_Array['device_date_stamp'] = "Moving--".$Result_Array['device_date_stamp'];						}						//Stopped						else if($Data_Cur_Status_Val == 'Stopped'){							$Result_Array['device_date_stamp'] = "Stopped--".$Result_Array['device_date_stamp'];						}						//Idle						else if($Data_Cur_Status_Val == 'Idle'){							$Result_Array['device_date_stamp'] = "Idle--".$Result_Array['device_date_stamp'];						}						//Unknown						else{							$Result_Array['device_date_stamp'] = "Unknown--".$Result_Array['device_date_stamp'];						}					}					// Calculation for Total KM Travelled							$Total_KM_Value+= Diff_Between_Odameter($KM_Pre_Value, $Result_Array['odometer']);										// Assigning the previous value					$Data_Pre_Array = array($Data_Cur_Status_Val, $Result_Array['device_epoch_time']);					$KM_Pre_Value = $Result_Array['odometer'];										// For debug only					//echo $i."-----".$Result_Array['device_date_stamp']."<br />";										$i++;				}				}		}	    				$Final_Result = Add_Vehicle_Status_Diff_AddDiff($Speed_Array, $All_DateTime_Diff, $All_DateTime_NE_Diff, $DateTime_Moving_Diff, $DateTime_Stopped_Diff, $DateTime_Idle_Diff, $DateTime_Unknown_Diff, $Decision_Maker_Moving_Diff, $Decision_Maker_Stopped_Diff, $Decision_Maker_Idle_Diff, $Decision_Maker_Unknown_Diff,$Total_KM_Value);				return $Final_Result;	}				############################################	#	#   Date Difference between dates	#	############################################			function Date_Range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {		$dates = array();		$current = strtotime($first);		$last = strtotime($last);		while( $current <= $last ) {			$dates[] = date($output_format, $current);			$current = strtotime($step, $current);		}		return $dates;	}			######################################	#	#       Difference between Odameter	#	############################################		function Diff_Between_Odameter($Previous_Value, $Current_Value){		//echo "---".$Previous_Value."---".$Current_Value."<br />";		$Result = null;		$Odometer_Diff = 0;				if(!empty($Previous_Value) && !empty($Current_Value)){				$Odometer_Diff = $Current_Value - $Previous_Value;			$Result = $Odometer_Diff;		}			else{			$Result = null;		}		return $Result;	}	?>