  <?php
	include("header_inner.php");
  ?>

  <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Daily Summary Detail <?php if(isset($_REQUEST['imei'])) echo "- ".$vehicle_nos[$_REQUEST['imei']]?>
                        <small></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home > </a></li> 
						<i></i> Reports ></a></li>
                        <li class="active">Daily Summary Detail</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

					<?php
						include_once("daily_summary_report_new.php");
					?>					
                    
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
  <?php
	include("footer_table.php");
  ?>
