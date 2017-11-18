<?php 

session_start();

include_once __DIR__ . "/api/common/constants.php";
include_once __DIR__ . "/api/common/config.php";
include_once __DIR__ . "/api/common/util.php";
include_once __DIR__ . "/api/common/http.php";
include_once __DIR__ . "/api/common/common.php";

if(isset($_SESSION["loggedIn"]) && ($_SESSION["loggedIn"] === true)){	
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Enteris :: Enterprize Indexing and Search</title>
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta content="Enteris, Enterprize, Indexing, Search, Desktop" name="keywords">
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<!-- Bootstrap Core CSS -->
		<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
		<!-- Custom CSS -->
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<!-- Graph CSS -->
		<link href="css/font-awesome.css" rel="stylesheet">
		<!-- jQuery -->
		<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'>
		<!-- lined-icons -->
		<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
		<!-- //lined-icons -->
		<script src="js/jquery-1.10.2.min.js"></script>
	</head>
	<body>
		<div class="page-container">
			<!--/content-inner-->
			<div class="left-content">
				<div class="inner-content">
					<!-- header-starts -->
					<div class="header-section">
						<!--menu-right-->
						<div class="top_menu" align="right">
							<!--/profile_details-->
							<div>
								<ul class="nofitications-dropdown">
									<li class="dropdown note">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
											<i class="fa fa-envelope-o"></i>
											<span class="badge">3</span>
										</a>
									</li>
								</ul>
							</div>
							<!--//profile_details-->
						</div>
						<!--//menu-right-->
						<div class="clearfix"></div>
					</div>
					<!-- //header-ends -->
					<div class="outter-wp">
						<!--custom-widgets-->
						<div>
							<?php
							
								$page = __DIR__."/pages/home.php";							
								if(isset($_GET["page"])){
									
									$page = __DIR__."/pages/".$_GET["page"].".php";
								}	
								if(!file_exists($page)){
									echo "<h2>404: File not found</h2>";
								}
								else{
									include_once $page;
								}								
							?>
						</div>
						<!--//custom-widgets-->
					</div>
				</div>
				<!--//content-inner-->
				<!--/sidebar-menu-->
				<div class="sidebar-menu">
					<header class="logo">
						<a href="#" class="sidebar-icon">
							<span class="fa fa-bars"></span>
						</a>
						<a href="index.html">
							<span id="logo">
								<h1>Dashboard</h1>
							</span>
							<!--<img id="logo" src="" alt="Logo"/>-->
						</a>
					</header>
					<div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
					<!--/down-->
					<div class="down">
						<ul>
							<li>
								<a class="tooltips" href="api/user/logout.php">
									<span>Log out</span>
									<i class="lnr lnr-power-switch"></i>
								</a>
							</li>
						</ul>
							<?php 
								$user = $_SESSION["user"];
								$fullname = $user->{"firstname"}->{"value"} . " " . $user->{"lastname"}->{"value"};
							?>
						<a>
							<span class=" name-caret" title="<?php echo $fullname;?>">
							<?php 
								echo substr($fullname, 0, 15) . (strlen($fullname)>15?"...":"");
							?>
							</span>
						</a>
					</div>
					<!--//down-->
					<div class="menu">
						<ul id="menu" >
							<?php if(checkFeatureElement(FE_Admin_Control)){ ?>
							<li id="menu-academico" >
								<a href="#">
									<i class="fa fa-table"></i>
									<span> Admin</span>
									<span class="fa fa-angle-right" style="float: right"></span>
								</a>
								<ul id="menu-academico-sub" >
									<?php if(checkFeatureElement(FE_Define_Feature_Elements)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=feature-elements"> Feature Elements</a>
									</li>
									<?php } ?>
									<?php if(checkFeatureElement(FE_Define_Roles)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=roles"> Roles</a>
									</li>
									<?php } ?>
									<?php if(checkFeatureElement(FE_Manage_Users)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=users"> Users</a>
									</li>
									<?php } ?>
									<?php if(checkFeatureElement(FE_Define_Subscriptions)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=subscriptions"> Subscriptions</a>
									</li>
									<?php } ?>
									<?php if(checkFeatureElement(FE_See_History)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=history"> History</a>
									</li>
									<?php } ?>
									<?php if(checkFeatureElement(FE_See_Payments)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=payments"> Payments</a>
									</li>
									<?php } ?>
									<?php if(checkFeatureElement(FE_Define_Payment_Methods)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=payment-methods"> Payment Methods</a>
									</li>
									<?php } ?>
								</ul>
							</li>
							<?php } ?>
							<li id="menu-academico" >
								<a href="#">
									<i class="fa fa-table"></i>
									<span>Enteris</span>
									<span class="fa fa-angle-right" style="float: right"></span>
								</a>
								<ul id="menu-academico-sub" >
									<?php if(checkFeatureElement(FE_Search_Capability)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php"> Search</a>
									</li>
									<?php } ?>
									<?php if(checkFeatureElement(FE_Download_Enteris)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=download"> Download</a>
									</li>
									<?php } ?>
								</ul>
							</li>
							<li id="menu-academico" >
								<a href="#">
									<i class="fa fa-table"></i>
									<span> Subscription</span>
									<span class="fa fa-angle-right" style="float: right"></span>
								</a>
								<ul id="menu-academico-sub" >
									<?php if(checkFeatureElement(FE_Renew_Subscription)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=renew"> Payments and Renewels</a>
									</li>
									<?php } ?>
									<?php if(checkFeatureElement(FE_Cancel_Subscription)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=renew"> Cancel</a>
									</li>
									<?php } ?>
								</ul>
							</li>
							<li id="menu-academico">
								<a href="#">
									<i class="lnr lnr-layers"></i>
									<span>User</span>
									<span class="fa fa-angle-right" style="float: right"></span>
								</a>
								<ul id="menu-academico-sub" >
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=profile"> Profile</a>
									</li>
									<?php if(checkFeatureElement(FE_Update_Payment_Details)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="index.php?page=payment-details"> Payment Details</a>
									</li>
									<?php } ?>
									<?php if(checkFeatureElement(FE_Update_Password)){ ?>
									<li id="menu-academico-avaliacoes" >
										<a href="password.php"> Change Password</a>
									</li>
									<?php } ?>
								</ul>
							</li>
						</ul>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<script>
				var toggle = true;
				$(".sidebar-icon").click(function() {                
					
					if (toggle)
					{
						$(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
						$("#menu span").css({"position":"absolute"});
					}
					else
					{
						$(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
						setTimeout(function() {
						  $("#menu span").css({"position":"relative"});
						}, 400);
					}
					toggle = !toggle;
				});
			</script>
			<script src="js/jquery.nicescroll.js"></script>
		</div>
	</body>
</html>
<?php 
}
else{
	session_regenerate_id(true);
  	header('Location: login.php');
	session_write_close();
}
?>