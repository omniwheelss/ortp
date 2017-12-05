  <?php
	include("header.php");
  ?>
	<?php
	if(isset($_REQUEST['id'])){
		$imei = base64_decode($_REQUEST['id']);
		$Mysql_Query2 = "select *,b.vehicle_no as vehicle_no from device_data a left join device_master b on a.imei =b.imei where a.imei = '".$imei."' order by a.id desc limit 1";
		$Mysql_Query_Result2 = mysql_query($Mysql_Query2) or die(mysql_error());
		
		$device_count2 = mysql_num_rows($Mysql_Query_Result2);
		if($device_count2 ==1){
			$device_status = mysql_fetch_array($Mysql_Query_Result2);
			$gps_move_status = $device_status['gps_move_status'];
			$device_date_stamp = $device_status['device_date_stamp'];
			$mDevice_Date = date("d-m-Y",strtotime($device_date_stamp));
			$mDevice_Time = date("H:i:s",strtotime($device_date_stamp));
			$ign = $device_status['ign'];
			$latitude = $device_status['latitude'];
			$longitude = $device_status['longitude'];
			$location = $device_status['location'];
			$message[] = $location;
			$speed = $device_status['speed'];
			$id = $device_status['id'];
			$vehicle_no = $device_status['vehicle_no'];
		}	
	}
	
	?>
  <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Current Location Of Vehicle - <b><?=$vehicle_no?>
                        <small></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Current Location Of Vehicle</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

					<?php
						//include_once("box.php");
					?>					
                    <!-- top row -->
                    <div class="row">
                        <div class="col-xs-12 connectedSortable">
						<?php
							//include_once("device_list.php");
						?>					
                        </div><!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
                        <section class="col-lg-12 connectedSortable">
                            <!-- Map box -->
                            <div class="box box-primary">
                                <!--<div class="box-header">
                                    <!-- tools box --
                                  <div class="pull-right box-tools">                                        
                                        <!--<button class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>--
                                        <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools --

                                    <i class="fa fa-map-marker"></i>
                                    <h3 class="box-title">
                                       Current Location of Vehicle - <b><?=$vehicle_no?></b>
                                    </h3>-->
									<script type="text/javascript">
										
										function initialize() {

											var message;
											
										  var mapOptions = {
											center: new google.maps.LatLng(<?=$latitude?>,<?=$longitude?>),
											zoom: <?=$currentlocationzoomlvl;?>
										  };

										  var map = new google.maps.Map(document.getElementById('map'),
											  mapOptions);
											<?php
											if($device_count2 ==1){
												$i = 0;
											?>
										  // Add 5 markers to the map at random locations
											var position = new google.maps.LatLng(
												<?=$latitude?>,
												<?=$longitude?>);
											var marker = new google.maps.Marker({
											  position: position,
											  map: map
											});
											marker.setIcon('./img/map_icons/bus3.png');
											marker.setTitle((<?=$i?> + 1).toString());
											attachSecretMessage(marker, <?=$i?>);
										<?php
												if(count($message) > 0)
													//$messages = "'<div  style=\"text-align: center;\" ><b>Vehicle</b>: ".$vehicle_no."<br><b>Date</b>:".$mDevice_Date."  <b>Time</b>:".$mDevice_Time." <b>Speed</b>:".$speed."<br>@<br>".join("','",$message)."</div>'";
												
													$Map_DateTime = date("d-M-Y g:ia",strtotime($device_date_stamp));
													
													$messages = "'<div><table cellpadding=\"5\" cellspacing=\"5\" border=\"0\"><tr><td align=\"left\" valign=\"top\" width=\"50px\" colspan=\"2\" style=\"color:red;\"><b>Current Location Info</b></td></tr><tr><td align=\"left\" valign=\"top\" width=\"90px\"><b>Vehicle</b></td><td>".$vehicle_no."</td></tr><tr><td align=\"left\" valign=\"top\" width=\"90px\"><b>Date & Time</b></td><td align=\"left\" valign=\"top\">".$Map_DateTime."</td></tr><tr><td align=\"left\" valign=\"top\"><b>Location</b></td><td align=\"left\" valign=\"top\">".$location."</td></tr></table></div>'";
												
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
										  
											infowindow.open(marker.get('map'), marker);
										  google.maps.event.addListener(marker, 'click', function() {
											infowindow.open(marker.get('map'), marker);
										  });
										}

										google.maps.event.addDomListener(window, 'load', initialize);

									</script>			
                                <!--</div>-->
                                <div class="box-body no-padding">
                                    <div id="map" style="height: 500px;"></div>
                                </div><!-- /.box-body-->
                                <div class="box-footer">
                                </div>
                            </div>
                            <!-- /.box -->


                        </section><!-- right col -->
                    </div><!-- /.row (main row) -->

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
  <?php
	include("footer.php");
  ?>