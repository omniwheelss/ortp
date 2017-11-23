  <?php
	include("header_inner.php");
  ?>

  <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Change Password Assistance
                        <small></small>
                    </h1>
                    <ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home ></a></li>
			<i></i> Settings ></a></li>	
			<li class="active">Change Password</li>
			
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

					<?php
						include_once("change_password_inner.php");
					?>					
                    
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
  <?php
	include("footer_table.php");
  ?>
