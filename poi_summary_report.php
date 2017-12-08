<?php
	error_reporting(0);
	
	$Dates_POI_Array = Dates_POI();
?>
		<form name="vehicle_report" method="post" action="">
			<div class="row">
                    <div class="col-xs-12">
						<div class="box">
                                <div class="box-header">
                                    <!--<h3 class="box-title">Vehicle Summary Report</h3>-->
                                </div><!-- /.box-header -->								
									 <div class="form-group">
									 <label style="float:left; margin-left:10px;">Select Vehicle <span style="color:#FF0000"> * </span><div id="vehicle_report_imei_errorloc" style="color:#FF0000" class="error"></div></label>
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
										<label style="float:left; margin-left:20px; ">Date: <span style="color:#FF0000"> * </span>
										<div id="vehicle_report_reservation_errorloc" style="color:#FF0000" class="error"></div>
										</label>     
										<div class="input-group" style="width:40%; float:left; margin-left:10px; ">       
										<div class="input-group-addon">            
										<i class="fa fa-calendar"></i>           
										</div>
										<?php
										if(isset($_REQUEST['reservation'])){	
											$reservation = $_REQUEST['reservation'];	
										}		
										else{	
											$reservation = date("m/d/Y")." - ". date("m/d/Y");	
										}
										?>     
										<input type="text" class="form-control pull-right" id="reservation" name="reservation" value="<?=$reservation?>" />
										<!--
										<select class="form-control" style="width:150px; margin-left:5px; float:left;"  name="reservation1" id ="reservation1">
											<option value='0'>--Select Date--</option>
											<?php
												if($_REQUEST['imei'])
													$_REQUEST['imei'] = $_REQUEST['imei'];
												else
													$_REQUEST['imei'] = '';	
												
												
												foreach($Dates_POI_Array as $key => $Dates_POI_Val){
											?>
												<option value="<?=$key?>" <?=($key == $_REQUEST['reservation1']?'selected=selected' : '')?>><?=$Dates_POI_Val?></option>
											<?php
												}
											?>
										</select>
										   -->                                     
										</div><!-- /.input group -->			
										<!--<button class="btn btn-primary btn-sm" style="float:left; margin-left:10px; margin-top:2px;" type="submit">Search</button>-->
										<input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" style="float:left; margin-left:10px; margin-top:2px;" value="Search" />
										
									</div>			
									<br /><hr />
								
								<!--Validation starts-->
								<script type="text/javascript" src="js/validator.js"></script>
								<script language="JavaScript" type="text/javascript" xml:space="preserve">
								//<![CDATA[
								//You should create the validator only after the definition of the HTML form
								var frmvalidator  = new Validator('vehicle_report');
								frmvalidator.EnableOnPageErrorDisplay();        
								frmvalidator.addValidation("imei","dontselect=0","Please Select Vehicle");
								frmvalidator.addValidation("reservation","req","Field Should not be left Empty");
								</script>
								<!--Validation Ends-->
									
								<?php				
								if(isset($_REQUEST['reservation'])){		
								
									$Date_Search_Exp = explode("-",$_REQUEST['reservation']);	
									$From_Date = date("Y-m-d", strtotime($Date_Search_Exp[0]));
									$To_Date = date("Y-m-d", strtotime($Date_Search_Exp[1]));
									$From_Date = $From_Date." 00:00:00";
									$To_Date = $To_Date." 23:59:59";
									
									echo "<br />";								
								?>
									<?php
									//Getting Device List
									if(isset($_REQUEST['imei']))
										$IMEI = $_REQUEST['imei'];
										$Geofence_List = Geofence_List($IMEI, $From_Date, $To_Date);
									?>	
								
									<?php
									if(count($Geofence_List) > 0){
									?>										
									<table style="height:20px;">
										<tr>
											<td width="10px;">&nbsp;</td>
											<td style="background-color:#FDF6BF; width:20px; height:10px;">&nbsp;</td>
											<td>&nbsp;&nbsp;Vehicle Travelled in Unassigned Route</td>
										</tr>
									</table>
										<?php
										}
										?>										
                                <div class="box-body table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>SNo</th>
                                                <th>Vehicle</th>
                                                <th>Trip Starts From</th>
                                                <th>Start Date Time</th>
                                                <th>Trip End At</th>
                                                <th>End Date Time</th>
												<th>Total Travel Time</th>
												<th>Plot</th>
											</tr>
                                        </thead>
                                        <tbody>
										<?php
										$Device_List_Array = Device_List($User_Account_ID);
										$Device_Info_Array = Device_Info($IMEI);

										if(count($Geofence_List) > 0){
											$i = 1;
											$Records_Exist = 0;
											foreach($Geofence_List as $Geofence_List_Val){
												
												$In_Date_Stamp = $Geofence_List_Val['in_date_stamp'];
												$In_Trip_Index = $Geofence_List_Val['in_trip_index'];
												$Out_Date_Stamp = $Geofence_List_Val['out_date_stamp'];
												$Out_Trip_Index = $Geofence_List_Val['out_trip_index'];
												$In_Geofence_Details = Geofence_Details($In_Trip_Index);
												$Out_Geofence_Details = Geofence_Details($Out_Trip_Index);												
												if(!empty($Out_Date_Stamp))
													$Out_Date_Stamp_Final = date("d-M-Y g:ia",strtotime($Out_Date_Stamp));
												else
													$Out_Date_Stamp_Final = "";
												if(!empty($In_Date_Stamp))
													$In_Date_Stamp_Final = date("d-M-Y g:ia",strtotime($In_Date_Stamp));
												else
													$In_Date_Stamp_Final = "";
												
												//Find a difference between tables
												$Get_EpochDiff = Get_EpochDiff(strtotime($Out_Date_Stamp), strtotime($In_Date_Stamp));
												$Total_Pocket_Time = Epoch_To_Time($Get_EpochDiff);
												if($Out_Geofence_Details['name'] == $In_Geofence_Details['name'])
													$Wrong_Rec_Cls = 'style="background-color:#FDF6BF"';
												else	
													$Wrong_Rec_Cls = '';
												
												$Route_Map_Time = date("m/d/Y g:i A", strtotime($Out_Date_Stamp_Final))." - ". date("m/d/Y g:i A", strtotime($In_Date_Stamp_Final));
											?>
											<tr>
												<td <?=$Wrong_Rec_Cls?>><?=$i?></td>
												<td <?=$Wrong_Rec_Cls?>><?=$Device_Info_Array[$IMEI]['vehicle_no']?></td>
												<td <?=$Wrong_Rec_Cls?>><?=$Out_Geofence_Details['name']?></td>
												<td <?=$Wrong_Rec_Cls?>><?=$Out_Date_Stamp_Final?></td>
												<td <?=$Wrong_Rec_Cls?>><?=$In_Geofence_Details['name']?></td>
												<td <?=$Wrong_Rec_Cls?>><?=$In_Date_Stamp_Final?></td>
												<td <?=$Wrong_Rec_Cls?>><?=$Total_Pocket_Time?></td>
												<td <?=$Wrong_Rec_Cls?>>
												<?php
												$Records_Exist = 1;
												if(!empty($In_Geofence_Details['name'])){
												?>
												<a href="movement_history_map.php?imei=<?=$IMEI?>&reservationtime=<?=$Route_Map_Time?>&submit=Search"><img src="./img/plot.png" width="15" height="15"></a>
												<?php
												}
												?>
												</td>
											</tr>
											<?php
												$i++;
											}
										}
										else{
											//echo "Records not found";
										}										
										?>
										<?php
										if($Records_Exist == 0){
										?>										
												<tr>
													<td colspan="8" style='color:red;'>Records not found</td>
												</tr>												
										<?php
										}
										?>										

                                        </tbody>
                                        <!--<tfoot>
                                            <tr>
                                                <th>SNo</th>
                                                <th>Date Time</th>
                                                <th>Location</th>
                                                <th>Status</th>
                                                <th>Ignition Status</th>
                                                <th>Speed</th>
                                            </tr>
                                        </tfoot>-->
                                    </table>	
									<br /><br />
									<?php
									if(count($Geofence_List) > 0){
									?>										
									<table style="height:20px;">
										<tr>
											<td style="background-color:#FDF6BF; width:20px; height:10px;">&nbsp;</td>
											<td>&nbsp;&nbsp;Vehicle Travelled in Unassigned Route</td>
										</tr>
									</table>
										<?php
										}
										?>										
									
									<?php
									}
									?>
                                </div><!-- /.box-body -->
								</div><!-- /.box -->
                        </div>
                    </div>
					</form>
							