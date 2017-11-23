<?php
	//ob_start();
?>

<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>Vehicle Tracking System</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="./css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="./css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bg-black">
		<?php
			include("includes.php");
			
			if(!empty($_COOKIE[$Cook_Name])){
				header("Location: home.php");
				exit;
			}
			
			if(isset($_REQUEST['login'])){
				$Username = $_REQUEST['userid'];
				$Password = md5($_REQUEST['password']);
				$Login_Sql = "select * from user_master where username = '$Username' and password = '$Password'";
				$Login_Run = mysql_query($Login_Sql) or die(mysql_error());
				$Login_Count = mysql_num_rows($Login_Run);
				if($Login_Count == 1){
					$Login_Result=mysql_fetch_array($Login_Run);
					$Random = rand(0,99999);
					$Cook_Variable = $Login_Result['username']."|".$Random."|".$Login_Result['user_type_id']."|".$Login_Result['user_account_id'];
					setcookie($Cook_Name, $Cook_Variable, time()+86400);
					header("Location: home.php");
					exit;
				}
				else{
					$war_msg = "Oops! Username or Password wrong";
				}
			}
		?>		
		<?php
			if(isset($war_msg)){
		?>
		<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<b><?=$war_msg?></b>
		 </div>
		<?php
			}
		?>							
        <div class="form-box" id="login-box">
            <div class="header">Sign In</div>
            <form action="" method="post">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" name="userid" class="form-control" placeholder="User ID"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                    </div>          
                    <div class="form-group">
                        <input type="checkbox" name="remember_me"/> Remember me
                    </div>
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block" name="login">Sign me in</button>  
                    
                    <!--<p><a href="#">I forgot my password</a></p>-->
                    
                    <!--<a href="register.html" class="text-center">Register a new membership</a>-->
                </div>
            </form>

			<!--
            <div class="margin text-center">
                <span>Sign in using social networks</span>
                <br/>
                <button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button>
                <button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button>
                <button class="btn bg-red btn-circle"><i class="fa fa-google-plus"></i></button>

            </div>
			-->
        </div>


        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="./js/bootstrap.min.js" type="text/javascript"></script>        

    </body>
</html>
