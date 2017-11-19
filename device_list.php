
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Vehicle Last Known Location</h3>
				<div class="box-tools">
					<!--<div class="input-group">
						<input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
						<div class="input-group-btn">
							<button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>-->
			</div><!-- /.box-header -->
			<div class="box-body table-responsive no-padding">
				<table class="table table-hover">
					<tr>
						<th>Sno</th>
						<th>Date & Time</th>
						<!--<th>Working Status</th>-->
						<th>Vehicle No</th>
						<th>Vehicle Status</th>
						<th>Ignition Status</th>
						<th>Speed</th>
						<th>Current Location</th>
						<th>Plot</th>
					</tr>
					<?php
					
					//Getting Device List
					$Device_List_Array = Device_List($User_Account_ID);

					if(count($Device_List_Array) > 0){
						$i = 1;
						foreach($Device_List_Array as $Device_List_Val){
							$IMEI = $Device_List_Val['imei'];
							// Getting Device Status
							$Device_Status_Array = Device_Status($Device_List_Val['imei']);
							$Status_Icon = '<img src="./img/'.$Device_Status_Array['status_icon'].'" width="13px" height="13px" style="margin-top:-3px;" >&nbsp;&nbsp;';
							$IMEI_Encrypt = base64_encode($IMEI);
							
							//Speed Calculation
							round($Device_Status_Array['speed']) > 0? $Speed = round($Device_Status_Array['speed'])."km" : $Speed = 0;

						?>
						<tr>
							<td><?=$i?></td>
							<td><?=date("d-M-Y g:ia",strtotime($Device_Status_Array['device_date_stamp']))?></td>
							<td title="<?=$Device_List_Val['imei']?>"><?=$Device_List_Val['vehicle_no']?></td>
							<td><?=$Status_Icon?><?=$Device_Status_Array['device_status']?></td>
							<td><span><?=$Device_Status_Array['ign']?></span></td>
							<td><?=$Speed?></td>
							<td><?=$Device_Status_Array['location']?></td>
							<td><a href="current_location.php?id=<?=$IMEI_Encrypt?>"><img src="./img/plot.png" width="15" height="15" title="Show Current Location on Map"></a></td>
						</tr>
						<?php
							$i++;
						}
					}
					else{
						echo "Records not found";
					}										
						?>
					<!--<tr>
						<td>219</td>
						<td>Jane Doe</td>
						<td>11-7-2014</td>
						<td><span class="label label-warning">Pending</span></td>
						<td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
					</tr>
					<tr>
						<td>657</td>
						<td>Bob Doe</td>
						<td>11-7-2014</td>
						<td><span class="label label-primary">Approved</span></td>
						<td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
					</tr>
					<tr>
						<td>175</td>
						<td>Mike Doe</td>
						<td>11-7-2014</td>
						<td><span class="label label-danger">Denied</span></td>
						<td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
					</tr>-->
				</table>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
</div>
<?php
	if(count($Device_List_Array) > 0){
		$randomLatitude_key =array_rand($latitude,1);
		$randomLongitude_key =array_rand($longitude,1);
	}	
?>
<script type="text/javascript">
	
	function initialize() {
		var message;
	  var mapOptions = {
		zoom: <?=$homepagezoomlevel;?>,
		center: new google.maps.LatLng(<?=$latlonArray[$randomLatitude_key][0]?>, <?=$latlonArray[$randomLongitude_key][1]?>)
	  };

	  var map = new google.maps.Map(document.getElementById('map'),
		  mapOptions);
		<?php
		if(count($device_list1) > 0){
			$i = 0;
			foreach($device_list1 as $device_list_val){		
				$imei = $device_list_val['imei'];	
				$mDevice_Date = date("d-m-Y",strtotime($device_date_stamp[$imei]));
				$mDevice_Time = date("H:i:s",strtotime($device_date_stamp[$imei]));
				$Map_DateTime[$imei] = date("d-M-Y g:ia",strtotime($device_date_stamp[$imei]));
				//$message[] = "<div  style=\"text-align: center;\" ><table><span align=\"left\" valign=\"top\" width=\"50px\" colspan=\"2\" style=\"color:red;\"><b>Current Location Info</b></span><b>Vehicle</b>: ".$device_list_val['vehicle_no']."<br><b>Date</b>: ".$Map_DateTime[$imei]." <b>Speed</b>:".$speed[$imei]."<br>@<br>".$location[$imei]."</div>";
				
				$message[] = "<div><table cellpadding=\"5\" cellspacing=\"5\" border=\"0\"><tr><td align=\"left\" valign=\"top\" colspan=\"2\" style=\"color:red;\"><b>Current Location Info</b></td></tr><tr><td align=\"left\" valign=\"top\" width=\"90px\"><b>Vehicle</b></td><td>".$device_list_val["vehicle_no"]."</td></tr><tr><td align=\"left\" valign=\"top\" width=\"90px\"><b>Date & Time</b></td><td align=\"left\" valign=\"top\">". $Map_DateTime[$imei]."</td></tr><tr><td align=\"left\" valign=\"top\"><b>Location</b></td><td align=\"left\" valign=\"top\">".$location[$imei]."</td></tr></table></div>";
				
		?>
	  // Add 5 markers to the map at random locations
		var position = new google.maps.LatLng(
			<?=$latitude[$imei]?>,
			<?=$longitude[$imei]?>);
		var marker = new google.maps.Marker({
		  position: position,
		  map: map
		});
		marker.setIcon('./img/map_icons/bus3.png');
		marker.setTitle((<?=$i?> + 1).toString());
		attachSecretMessage(marker, <?=$i?>);
	<?php
			$i++;
		}
			if(count($message) > 0)
				$messages = "'".join("','",$message)."'";
		}
			
	?>						
	}

	// The five markers show a secret message when clicked
	// but that message is not within the marker's instance data
	function attachSecretMessage(marker, num) {
	  var message = [<?=$messages?>];
	  var infowindow = new google.maps.InfoWindow({
		content: message[num]
	  });

	  google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(marker.get('map'), marker);
	  });
	}

	google.maps.event.addDomListener(window, 'load', initialize);

</script>			
