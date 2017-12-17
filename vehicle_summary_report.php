<?php	error_reporting(0);?>
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
								frmvalidator.addValidation("reservationtime","req","Field Should not be left Empty");
								</script>
								<!--Validation Ends-->
									
								<?php				
								if(isset($_REQUEST['reservationtime'])){		
								$Date_Search_Exp = explode("-",$_REQUEST['reservationtime']);	
								$From_Time1 = substr($Date_Search_Exp[0],11,10);
								$From_Time = date("H:i:s",strtotime($From_Time1));	
								$From_Date = date("Y-m-d",strtotime($Date_Search_Exp[0]))." ".$From_Time;	
								
								$To_Time1 = substr($Date_Search_Exp[1],12,10);
								$To_Time = date("H:i:s",strtotime($To_Time1));	
								$To_Date = date("Y-m-d",strtotime($Date_Search_Exp[1]))." ".$To_Time;
								echo "<br />";
								?>
								
                                <div class="box-body table-responsive">
                                    <table id="" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>SNo</th>
                                                <th>Date & Time</th>
                                                <th>Vehicle</th>
                                                <th>Location</th>
                                                <th>Status</th>
                                                <th>Ignition Status</th>
                                                <th>Speed</th>
                                                <!--<th>Plot & POI</th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php
										//Getting Device List
										if(isset($_REQUEST['imei']))
											$IMEI = $_REQUEST['imei'];
										
										$Device_List_Array = Device_List($User_Account_ID);
										$Device_Info_Array = Device_Info($IMEI);

										if(count($Device_List_Array) > 0){										
											$i = 1;
											$device_count2 = 0;
											$Mysql_Query2 = "select * from device_data where imei = '".$IMEI."' and device_date_stamp between '".$From_Date."' and '".$To_Date."' order by device_date_stamp asc";
											$Mysql_Query_Result2 = mysql_query($Mysql_Query2) or die(mysql_error());
											$device_count2 = mysql_num_rows($Mysql_Query_Result2);
											if($device_count2>=1){
												$vehicle_current_status = null;
												$Moving_Status_Count = 0;
												
												while($Vehicle_Summary_Array = mysql_fetch_array($Mysql_Query_Result2)){
													## Vechicle Status
													$device_date_stamp = $Vehicle_Summary_Array['device_date_stamp'];
													
													$Device_Epoch_Diff = Epoch_Diff(device_date_stamp);
													$Alert_Msg_Code = explode("|", $Vehicle_Summary_Array['alert_msg_code']);
													
													// Moving Status
													if($Vehicle_Summary_Array['live_data'] == 1 && $Vehicle_Summary_Array['speed'] > 10 && $Vehicle_Summary_Array['ign'] == 1){
														$Vehicle_Summary_Array['status'] = "Moving";
														$Vehicle_Summary_Array['ign'] = "On";
														$Vehicle_Summary_Array['status_icon'] = "green.png";
													}
													// Stopped Status
													else if($Vehicle_Summary_Array['live_data'] == 1 && $Vehicle_Summary_Array['speed'] == 0  && $Vehicle_Summary_Array['ign'] == 0 && $Alert_Msg_Code[0] != 'VI'){
														$Vehicle_Summary_Array['status'] = "Stopped";
														$Vehicle_Summary_Array['ign'] = "Off";
														$Vehicle_Summary_Array['status_icon'] = "red.png";
													}
													// Idle Status
													else if(
														($Vehicle_Summary_Array['live_data'] == 1 && $Vehicle_Summary_Array['speed'] <= 10 && $Vehicle_Summary_Array['ign'] == 1) || $Alert_Msg_Code[0] == 'VI'){
														$Vehicle_Summary_Array['status'] = "Idle";
														$Vehicle_Summary_Array['ign'] = "On";
														//$Vehicle_Summary_Array['speed'] = 0;
														$Vehicle_Summary_Array['status_icon'] = "orange.png";
													}
													
													
													//Speed Calculation
													round($Vehicle_Summary_Array['speed']) > 0? $Speed = round($Vehicle_Summary_Array['speed'])."km" : $Speed = 0;
													
													//Status Icon
													$Status_Icon = '<img src="./img/'.$Vehicle_Summary_Array['status_icon'].'" width="13px" height="13px" style="margin-top:-3px;" >&nbsp;&nbsp;';
													
											?>
											<tr>
												<td><?=$i?></td>
												<td><?=date("d-M-Y g:ia",strtotime($device_date_stamp))?></td>
												<td><?=$Device_Info_Array[$IMEI]['vehicle_no']?></td>
												<td width="50%"><?=$Vehicle_Summary_Array['location']?></td>
												<td><?=$Status_Icon?><?=$Vehicle_Summary_Array['status']?></td>
												<td align="center"><?=$Vehicle_Summary_Array['ign']?></td>
												<td><?=$Speed?></td>
											</tr>
											<?php
												$i++;
												}
											}
										}
										else{
											//echo "Records not found";
										}										
										?>
										<?php
										if($device_count2 == 0){
										?>										
												<tr>
													<td colspan="7" style='color:red;'>Records not found</td>
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
                                    </table>							<?php							}							?>
                                </div><!-- /.box-body -->                            </div><!-- /.box -->
                        </div>
                    </div>				</form>
							
