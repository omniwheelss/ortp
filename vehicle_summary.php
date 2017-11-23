  <?php
	include("header_inner.php");
  ?>

  <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Vehicle Summary Report<?php if(isset($_REQUEST['imei'])) echo "- ".$vehicle_nos[$_REQUEST['imei']]?>
                        <small></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home > </a></li> 
						<i></i> Reports ></a></li>
                        <li class="active">Vehicle Summary Report</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

					<?php
						include_once("vehicle_summary_report.php");
					?>					
                    
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
  <?php
	include("footer_table.php");
  ?>
