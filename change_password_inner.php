<?php

	// After submit
	if(isset($_REQUEST['pass_submit'])){
		
		$Old_Pass = $_REQUEST['Old_Pass'];
		$New_pass = $_REQUEST['Pass'];
		if(empty($Old_Pass) || empty($New_pass)){
			$Pass_Msg = "Please provide the password details as requested";
		}
		else{
			$Change_Password_Result = Change_Password($Old_Pass, $New_pass, $User_Account_ID);
			if($Change_Password_Result == 'Success'){
				$Pass_Msg = "Your new Password was updated successfully";
			}
			else{
				$Pass_Msg = "Your old password was wrong, Please try again";
			}
		}	
	}
?>

<!-- Main content -->

	<div class="row">
		<!-- left column -->
		<div class="col-md-12">
			<!-- general form elements -->
			<div class="box box-primary">
				<!-- form start -->
				<form role="form" method="post">
					<div class="box-body">
						<?php
							if(isset($Pass_Msg)){
								if($Change_Password_Result == 'Success'){
									$Alert = "success";
								}
								else{
									$Alert = "danger";
								}
						?>
						<div class="alert alert-<?=$Alert?> alert-dismissable">
							<i class="fa fa-ban"></i>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<b><?=$Pass_Msg?></b>
                         </div>
						<?php
							}
						?>						
						<div class="form-group">
							<label for="exampleInputEmail1">Please enter the current Password</label>
							<input type="password" class="form-control" id="Old_Pass" name="Old_Pass" placeholder="Enter your old Password">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">New Password</label>
							<input type="password" class="form-control" id="Pass" name="Pass" placeholder="Enter New Password">
						</div>
					</div><!-- /.box-body -->

					<div class="box-footer">
						<button type="submit" class="btn btn-primary" name="pass_submit">Submit</button>
						<div id="Output_Div" class="Change_Email_txt"></div>
					</div>
				</form>
			</div><!-- /.box -->

		</div><!--/.col (right) -->
	</div>   <!-- /.row -->

