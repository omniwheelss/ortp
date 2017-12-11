<?php	error_reporting(0);?>
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
									$From_Date = $Date_Search_Exp[0];
									$To_Date = $Date_Search_Exp[1];									
									$Date_Array = Date_Range($From_Date, $To_Date);

								?>
                                <div class="box-body table-responsive">
								
								
                                    <table id="" class="table table-bordered table-striped">
                                      <!--  <thead>
                                            <tr>
                                                <th>SNo</th>
                                                <th>Date Time</th>
                                                <th>Total Up Time</th>
                                                <th>Moving Time</th>
                                                <th>Stopped Time</th>
                                                <th>Idle Time</th>
                                            </tr>
                                        </thead>-->
                                        <tbody>
										<?php
										//Getting Device List
										if(isset($_REQUEST['imei']))
											$IMEI = $_REQUEST['imei'];
										
										$Device_List_Array = Device_List($User_Account_ID);
									
										if(count($Device_List_Array) > 0){										
											$i = 1;
											$Records_Exist = 0;
											foreach($Date_Array as $Date){
												// Getting all the data by status
												$Get_Summary = Get_Daily_Summary($Date, $IMEI);		
												$Query_Date = date("m/d/Y",strtotime($Date));;
												$Query_Date = $Query_Date." - ".$Query_Date;
												$Fuel_Msg = null;
												$KM_Msg = null;
												
												if(count($Get_Summary[1]) > 0){
													if(!empty($Get_Summary[3])){
														$CalloutCls = "green";
														$HRCls = "#d6e9c6";
													}	
													else{
														$CalloutCls = "danger";	
														$HRCls = "#dFb5b4";	
													}
													
													if($IMEI == '864547036439193'){
														$Fuel_Avg = 3.90;
													}
													else if($IMEI == '864547034419338'){
														$Fuel_Avg = 3.75;
													}
													else if($IMEI == '864547034266879'){
														$Fuel_Avg = 4.50;
													}
													// Fuel Calculation		
													
													if($Get_Summary[7] > 0){
														$Total_KM_Travelled = number_format($Get_Summary[7], 2);
														$Fuel_Travelled_Avg = number_format(($Get_Summary[7] / $Fuel_Avg), 2);
													}	
													else{
														$Total_KM_Travelled = $Get_Summary[7];
														$Fuel_Travelled_Avg = $Get_Summary[7] / $Fuel_Avg;
													}	
													
													if($Fuel_Travelled_Avg < 0)
														$Fuel_Msg = "<span class='text-red'> - Some Issues with Route</span>";
														
													if($Total_KM_Travelled < 0)
														$KM_Msg = "<span class='text-red'> - Some Issues with Route</span>";

											?>
												<!--<tr>
													<td><?=$i?></h3></td>
													<td><?=date("d-M-Y",strtotime($Date))?></td>
													<td><?=$Get_Summary[1]?></td>
													<td><?=$Get_Summary[3]?></td>
													<td><?=$Get_Summary[4]?></td>
													<td><?=$Get_Summary[5]?></td>
												
													<td><h3><?=$i?></h3></td>
													<td><h3><?=date("d-M-Y",strtotime($Date))?></h3></td>
													<td><h3 style='color:blue'><?=$Get_Summary[1]?></h3></td>
													<td><h3 style='color:green'><?=$Get_Summary[3]?></h3></td>
													<td><h3 style='color:red'><?=$Get_Summary[4]?></h3></td>
													<td><h3 style='color:orange'><?=$Get_Summary[5]?></h3></td>-->
												</tr>
												
												<div class="callout callout-<?=$CalloutCls?>">
													<h3><b>Date :</b> <?=date("d-M-Y",strtotime($Date))?>  | <b>Vehicle</b> : <?=$vehicle_nos[$IMEI]?></b></h3>
													<hr style='border-color:<?=$HRCls?>' />
													<p><b>Total Travelled Time : <?=$Get_Summary[3]?></b></p>
													<p><b>Total Travelled KM : <?=$Total_KM_Travelled?> KM</b> <?=$KM_Msg?></p>
													<p><b>Approx Fuel Usage : <?=$Fuel_Travelled_Avg?> Ltr</b> <?=$Fuel_Msg?></p>
													<p><b>Total Stopped Time : <?=$Get_Summary[4]?></b> </p>
													<p><b>Total Idle Time : <?=$Get_Summary[5]?></b> </p>
													<p class="text-grey"><b>Trip Summary : <u><a href="poi_summary.php?imei=<?=$IMEI?>&reservation=<?=$Query_Date?>&submit=Search">Trip Summary</a></u></b> </p>
												</div>								
											<?php
													$Records_Exist = 1;
												}
												else{
											?>
												<!--<tr>
													<td><?=$i?></td>
													<td><?=date("d-M-Y",strtotime($Date))?></td>
													<td colspan="4" align="center">Data Not Available</td>
												</tr>-->
											<?php	
												}
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
							