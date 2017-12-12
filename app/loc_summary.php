<?php
header("Content-Type: application/json");

	if($_REQUEST['key'] == '14583' && $_REQUEST['format'] == 'xml'){
		include_once("includes.php");
		if($_REQUEST['username'] != '' && $_REQUEST['password'] != ''){		
			$sql = "SELECT * from user_master WHERE username='" . $_REQUEST['username'] . "' and password='".md5($_REQUEST['password'])."'";
			$result = mysql_query( $sql );
			$record_count=mysql_num_rows($result);
			if($record_count > 0){
				$status = "true";
				$row = mysql_fetch_array($result);
				$account_id = $row['user_account_id'];
				
				// Getting DEVICE DETAILS
				$sql1 = "SELECT * from device_master WHERE user_account_id='" .$account_id. "'";
				$result1 = mysql_query( $sql1 );
				$record_count1=mysql_num_rows($result1);
				if($record_count1 > 0){
					while($row1 = mysql_fetch_array($result1)){
						$imei_array[] = $row1['imei'];
						$vehicle_no = $row1['vehicle_no'];
						$vehicle_no_by_imei[$row1['imei']] = $row1['vehicle_no'];
					}
				}
				
			}
			else{
				$status = "false";
			}
				// Getting LAST KNOWN 
				if(count($imei_array) > 0){
					foreach($imei_array as $imei_val){
						$sql1 = "SELECT * from device_data WHERE IMEI='" .$imei_val. "' order by device_date_stamp desc limit 1";
						$result1 = mysql_query( $sql1 );
						$record_count1=mysql_num_rows($result1);
						if($record_count1 > 0){
							while($row1 = mysql_fetch_array($result1)){
								if($row1['speed'] > 0)
									$device_health = "Moving";
								else if($row1['speed'] <= 0)
									$device_health = "Stopped";

								$location_details_array[] = array(
																'device_health' => $device_health,
																'asset_no' => $vehicle_no_by_imei[$row1['imei']],
																'speed' => $row1['speed'],
																'loc' => $row1['location'],
																'date' => date("d-m-Y",strtotime($row1['device_date_stamp'])),
																'time' => date("H:i:s",strtotime($row1['device_date_stamp']))
																);
							}									
						}
					}	
				}

		}
		else{
			$track = "Key not exist";
		}
	}
	else{
		$track = "Key not valid";
	}
	if(count($location_details_array) > 0){
		$response = array("location" => $location_details_array);
	}
	else{
		$response =  array("location" => array(null));
	}
//print_r($response);
echo json_encode($response);
?>