<?php	error_reporting(0);?>
<script src="./js/google_elabel.js" type="text/javascript"></script>
		<form name="vehicle_report" method="post" action="">
		<div class="row">
                    <div class="col-xs-12">
						<div class="box">
                                <div class="box-header">
                                    <!--<h3 class="box-title">Vehicle Summary Report</h3>-->
                                </div><!-- /.box-header -->								
									 <div class="form-group">										<label style="float:left; margin-left:10px;">Select Vehicle <span style="color:#FF0000"> * </span><div id="vehicle_report_imei_errorloc" style="color:#FF0000" class="error"></div></label>
										<select class="form-control" style="width:200px; margin-left:10px; float:left;" name="imei" id="imei">
											<option value='0'>--Select Vehicle--</option>
											<?php
												if($_REQUEST['imei'])
													$_REQUEST['imei'] = $_REQUEST['imei'];
												else
													$_REQUEST['imei'] = '';	
												
												
												foreach($device_list1 as $device_list_val){
											?>
												<option value="<?=$device_list_val['imei']?>" <?=($_REQUEST['imei'] == $device_list_val['imei']?'selected=selected' : '')?>><?=$device_list_val['vehicle_no']?></option>
											<?php
												}
											?>
										</select> 
										<label style="float:left; margin-left:20px; ">Date and time range: <span style="color:#FF0000"> * </span>
										<div id="vehicle_report_reservationtime_errorloc" style="color:#FF0000" class="error"></div>
										</label>     
										<div class="input-group" style="width:40%; float:left; margin-left:10px; ">       
										<div class="input-group-addon">            
										<i class="fa fa-clock-o"></i>           
										</div>						
										<?php					
										if(isset($_REQUEST['reservationtime'])){	
											$reservationtime = $_REQUEST['reservationtime'];	
										}		
										else{	
											$reservationtime = date("m/d/Y"). " 12:00 AM - ". date("m/d/Y g:i A");	
										}						
										?>     
										<input type="text" class="form-control pull-right" id="reservationtime" name="reservationtime" value="<?=$reservationtime?>" />
										                                        
										</div><!-- /.input group -->			
										<!--<button class="btn btn-primary btn-sm" style="float:left; margin-left:10px; margin-top:2px;" type="submit">Search</button>-->
										<input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" style="float:left; margin-left:10px; margin-top:2px;" value="Search" />
										
									</div><br />
								<?php				
								if(isset($_REQUEST['reservationtime'])){
								$Date_Search_Exp = explode("-",$_REQUEST['reservationtime']);	
								$From_Date = date("Y-m-d H:i:s",strtotime($Date_Search_Exp[0]));	
								$To_Date = date("Y-m-d H:i:s",strtotime($Date_Search_Exp[1]));		
								//$Date_Search = date()			
								?>
								
								<!--Validation starts-->
								
                                <div class="box-body table-responsive">
										<?php
										if(count($device_list1) > 0){
											$Row = 1;
											//foreach($device_list1 as $device_list_val){
											//if(isset($_REQUEST['imei'])){	
												$device_count2 = 0;
												$Mysql_Query2 = "select * from device_data where imei = '".$_REQUEST['imei']."' and device_date_stamp between '".$From_Date."' and '".$To_Date."' order by device_date_stamp asc";
												$Mysql_Query_Result2 = mysql_query($Mysql_Query2) or die(mysql_error());
												$device_count2 = mysql_num_rows($Mysql_Query_Result2);
												if($device_count2>=1){
													while($device_status = mysql_fetch_array($Mysql_Query_Result2)){
														$device_status_array[] = $device_status;
														$latitude = $device_status['latitude'];
														$longitude = $device_status['longitude'];
														$latlonArray[] = array($latitude,$longitude);
														$location = $device_status['location'];

														## Vechicle Status
														$Device_Date = date("d-m-Y",strtotime($device_date_stamp));
														$Device_Time = date("H:i:s",strtotime($device_date_stamp));
														$GMT_DRIFT = 5.5;
														$Device_Health = 0.5;
														$device_date_diff = date_diff_check($Device_Date,$Device_Time,$GMT_DRIFT);
														
														if($gps_move_status == 1 && $speed > 0 && $device_date_diff < $Device_Health){
															$vehicle_current_status = "Moving";
															$vehicle_current_status_class = "label label-success";
														}
														else if(($ign == 0 && $gps_move_status == 0 && $speed <  1.2) && $device_date_diff < $Device_Health){
															$vehicle_current_status = "Stopped";
															$vehicle_current_status_class = "label label-danger";
														}
														else if($ign == 1 && $gps_move_status == 2){
															$vehicle_current_status = "Idle";
															$vehicle_current_status_class = "label label-warning";
														}
														else if($device_date_diff > $Device_Health){
															$vehicle_current_status = "Unknown";
															$vehicle_current_status_class = "label label-warning";
														}
														
														#Ign Status
														if($ign == 1){
															$ign_status_msg = "ON";
															$ign_status_class = "label label-success";
														}
														else{
															$ign_status_msg = "OFF";
															$ign_status_class = "label label-danger";
														}
														$imei_encrypt = base64_encode($imei);
											
												?>
												<?php
													$Row++;
													}
												}
											//}	
										}
										else{
											echo "Records not found";
										}										
										?>
										<?php
										if($device_count2 == 0){
										?>										
												<tr>
													<td colspan="7" style="color:red;"><span  style="color:red;">Records not found</span></td>
												</tr>												
										<?php
										}
										?>										

                                        </tbody>
                                    </table>	
									<?php
									}							
									?>
                                </div><!-- /.box-body -->  
								</div><!-- /.box -->
                        </div>
                    </div>				
					</form>
						
					<?php
						if(count($device_list1) > 0){
							$randomLatLong_key =array_rand($latlonArray,1);
						}	
					?>
					<script type="text/javascript">
					
					function createMarker(point,icon,data) {
						var marker = new GMarker(point,icon); 
						GEvent.addListener(marker, "click", function() {    marker.openInfoWindowHtml(data);  });  
						return marker;
					}											
					function initialize() {
						var message;
					  var mapOptions = {
						zoom: 11,
						center: new google.maps.LatLng(<?=$latlonArray[$randomLatLong_key][0]?>, <?=$latlonArray[$randomLatLong_key][1]?>),
						mapTypeId: google.maps.MapTypeId.TERRAIN,
					  };

					  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
					  
					  var flightPlanCoordinates = [
							<?php
							if(count($device_count2) > 0){
								$i = 0;
								foreach($device_status_array as $device_status_val[$i]){		
									$device_status_final_array = $device_status_val[$i]
							?>				
								new google.maps.LatLng(<?=$device_status_final_array['latitude']?>, <?=$device_status_final_array['longitude']?>),
							<?php
									$i++;
								}
							}	
							?>
					  ];
					  
					  var flightPath = new google.maps.Polyline({
						path: flightPlanCoordinates,
						geodesic: true,
						strokeColor: '#FF0000',
						strokeOpacity: 1.0,
						strokeWeight: 2
					  });

					  flightPath.setMap(map);
					  
							<?php
							if(count($device_count2) > 0){
								$j = 0;
								foreach($device_status_array as $device_status_val[$j]){		
									$device_status_final_array = $device_status_val[$j];
									$Device_Date_Stamp = date("d-M-Y g:ia",strtotime($device_status_final_array['device_date_stamp']));
									
									$message[] = "<div><table cellpadding=\"5\" cellspacing=\"5\" border=\"0\"><tr><td align=\"left\" valign=\"top\" colspan=\"2\" style=\"color:red;\"><b>Current Location Info</b></td></tr><tr><td align=\"left\" valign=\"top\" width=\"90px\"><b>Vehicle</b></td><td>".$vehicle_nos[$device_status_final_array['imei']]."</td></tr><tr><td align=\"left\" valign=\"top\" width=\"90px\"><b>Date & Time</b></td><td align=\"left\" valign=\"top\">".$Device_Date_Stamp."</td></tr><tr><td align=\"left\" valign=\"top\"><b>Location</b></td><td align=\"left\" valign=\"top\">".$device_status_final_array['location']."</td></tr></table></div>";
									$latitude = $device_status_final_array['latitude'];
									$longitude = $device_status_final_array['longitude'];
									
									if($Previous_latitude != $latitude && $Previous_longitude != $longitude) {
							?>	
							var position = new google.maps.LatLng(
								<?=$latitude?>,
								<?=$longitude?>);
							var marker = new google.maps.Marker({
							  position: position,
							  //set.marker("img/map_icons/c.png"),
							  map: map
							});
							<?php
							if($j == 0){
							?>
							//marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
							marker.setIcon('./img/map_icons/grn.gif');
							
							<?php
							}
							else if ($j == ($device_count2-1)){
							?>
							//marker.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');
							marker.setIcon('./img/map_icons/red.gif');
							/*marker.addOverlay(createMarker(point, icon,"<? echo $message; ?>"));
							var label = new ELabel(new GLatLng(<?=$latitude?>, <?=$longitude?>), "End", "historyend",new GSize(0,0));
							marker.addOverlay(label);*/
							<?php
							}
							else{
							?>
							//marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
							marker.setIcon('./img/map_icons/blue1.gif');
							<?php
							}
							?>
							
							marker.setTitle((<?=$j?> + 1).toString());
							attachSecretMessage(marker, <?=$j?>);
							
						<?php
									}
								$j++;
								$Previous_latitude = $latitude;
								$Previous_longitude = $longitude;
								}
								if(count($message) > 0)
									$messages = "'".join("','",$message)."'";
							}
						?>

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

							//marker.setTitle((<?=$i?> + 1).toString());
							//attachSecretMessage(marker, <?=$i?>);
					  
					}

					google.maps.event.addDomListener(window, 'load', initialize);
					</script>			
					<?php
					if($device_count2 > 0){
					?>						
                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
                        <section class="col-lg-12 connectedSortable">
                            <!-- Map box -->
                            <div class="box box-primary">
                                <!-- <div class="box-header">
                                    tools box -->
                                    <!--<div class="pull-right box-tools">                                        
                                        <button class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>
                                        <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
                                    </div>-->

                                    <!--<i class="fa fa-map-marker"></i>
                                    <h3 class="box-title">
                                        Movement Summary
                                    </h3>-->
                                </div>
                                <div class="box-body no-padding">
                                    <div id="map" style="height: 400px;"></div>
                                </div><!-- /.box-body-->
                                <!--<div class="box-footer">
                                </div>-->
                            </div>
                            <!-- /.box -->


                        </section><!-- right col -->
                    </div><!-- /.row (main row) -->
					<?php
					}
					?>
   </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
  <?php
	include("footer.php");
  ?>							