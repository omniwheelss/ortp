<?php	error_reporting(0);?>
   <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCz2Um-Yd9G0XZ6xWn__jdR8vXAJCTBq98&callback=initMap">
  </script>
		<form name="vehicle_report" method="post" action="">
		<div class="row">
                    <div class="col-xs-12">
						<div class="box">
                                <div class="box-header">
                                    <!--<h3 class="box-title">Vehicle Summary Report</h3>-->
                                </div><!-- /.box-header -->								
									 <div class="form-group">
									 <label style="float:left; margin-left:10px;">Select Vehicle <span style="color:#FF0000"> * </span>
									 <div id="vehicle_report_imei_errorloc" style="color:#FF0000" class="error"></div></label>
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
										<label style="float:left; margin-left:20px; ">Date & Time: <span style="color:#FF0000"> * </span>
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

								
								<!--Validation starts-->
								<script type="text/javascript" src="js/validator.js"></script>
								<script language="JavaScript" type="text/javascript" xml:space="preserve">
								//<![CDATA[
								//You should create the validator only after the definition of the HTML form
								var frmvalidator  = new Validator('vehicle_report');
								frmvalidator.EnableOnPageErrorDisplay();        
								frmvalidator.addValidation("imei","dontselect=0","Please Select Vehicle");
								frmvalidator.addValidation("reservationtime","req","Field Should not be left Empty");
								</script>
								<!--Validation Ends-->									
									
								<?php				
								if(isset($_REQUEST['reservationtime'])){
									$Date_Search_Exp = explode("-",$_REQUEST['reservationtime']);	
									$From_Date = date("Y-m-d H:i:s",strtotime($Date_Search_Exp[0]));	
									$To_Date = date("Y-m-d H:i:s",strtotime($Date_Search_Exp[1]));		
								//$Date_Search = date()			
								?>

								
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
														//Getting Start and End Location alone
														$location = $device_status['location'];
														if($Row == 1 || $Row == $device_count2){
															if($Row == 1)
																	$Title = "Trip Starts";
															else if($Row == $device_count2)	
																	$Title = "Trip End";
															$LanLong_Array[] = array($latitude,$longitude, $location, $Title);
														}
									
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
							$randomLatLong_key =array_rand($LanLong_Array,1);
						}	
					?>
						<script type="text/javascript">
							var markers = [
								<?php
									foreach($LanLong_Array as $LanLong_Val){
								?>
								{
									"title": '<?=$LanLong_Val[3]?>',
									"lat": '<?=$LanLong_Val[0]?>',
									"lng": '<?=$LanLong_Val[1]?>',
									"icon": './img/map_icons/bus3.png',
									"description": '<b><?=$LanLong_Val[3]?></b><br /><?=$LanLong_Val[2]?>'
								},
								<?php
									}
								?>
					];
							
							window.onload = function () {
								var mapOptions = {
									center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
									icon: './img/map_icons/bus3.png',
									zoom: 10,
									mapTypeId: google.maps.MapTypeId.ROADMAP
								};
								var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
								var infoWindow = new google.maps.InfoWindow();
								var lat_lng = new Array();
								var latlngbounds = new google.maps.LatLngBounds();
								for (i = 0; i < markers.length; i++) {
									var data = markers[i]
									var myLatlng = new google.maps.LatLng(data.lat, data.lng);
									lat_lng.push(myLatlng);
									var marker = new google.maps.Marker({
										position: myLatlng,
										map: map,
										title: data.title
									});
									latlngbounds.extend(marker.position);
									(function (marker, data) {
										infoWindow.setContent(data.description);
										infoWindow.open(map, marker);
										google.maps.event.addListener(marker, "click", function (e) {
											infoWindow.setContent(data.description);
											infoWindow.open(map, marker);
										});
									})(marker, data);
								}
								map.setCenter(latlngbounds.getCenter());
								map.fitBounds(latlngbounds);

								//***********ROUTING****************//

								//Intialize the Path Array
								var path = new google.maps.MVCArray();

								//Intialize the Direction Service
								var service = new google.maps.DirectionsService();

								//Set the Path Stroke Color
								var poly = new google.maps.Polyline({ map: map, strokeColor: '#4986E7' });

								//Loop and Draw Path Route between the Points on MAP
								for (var i = 0; i < lat_lng.length; i++) {
									if ((i + 1) < lat_lng.length) {
										var src = lat_lng[i];
										var des = lat_lng[i + 1];
										path.push(src);
										poly.setPath(path);
										service.route({
											origin: src,
											destination: des,
											travelMode: google.maps.DirectionsTravelMode.DRIVING
										}, function (result, status) {
											if (status == google.maps.DirectionsStatus.OK) {
												for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
													path.push(result.routes[0].overview_path[i]);
												}
											}
										});
									}
								}
							}
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
                                    <div id="dvMap" style="height: 400px;"></div>
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