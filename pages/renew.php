<style>
	.package_title{
		padding-top: 2px;
		padding-bottom: 2px;
		padding-right: 10px;
		padding-left: 10px;
		font-size: 20px;
		font-weight: bold;
		text-align: center;
		color: #ffffff;
		background-color: #52b6ec
	}
	#package td{
		padding-top: 10px;
		padding-bottom: 10px;
		padding-right: 10px;
		padding-left: 10px;
		font-size: 12px;
		font-weight: bold;
		text-align: center;
	}
	
	
</style>

<?php if(checkFeatureElement(FE_Renew_Subscription)){ 
	$user = $_SESSION["user"];
	$email = $user->{"email"}->{"value"};
	include_once(__DIR__ . "/../api/subscription/list_for_user.php");
} else {?>
	<h3><?=NO_ACCESS_MESSAGE?></h3>
<?php } ?>
