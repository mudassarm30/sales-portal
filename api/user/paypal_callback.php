<?php 
	
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	require(__DIR__.'/../../lib/paypal/PaypalIPN.php');

	$ipn = new PaypalIPN();

	if (SANDBOX_ENABLED) {
		$ipn->useSandbox();
	}

	$verified = $ipn->verifyIPN();

	if($verified){
		
		$data_text = json_encode($_POST);
		$payment_status = $_POST["payment_status"]; 
		$detail = $_POST["custom"]; 
		$payment_gross = floatval($_POST["payment_gross"]);
		$item_number = intval($_POST["item_number"]);
		
		$response = getUserByEmail($detail);
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0)
		{
			$id = intval($response->{"restify"}->{"rows"}[0]->{"values"}->{"id"}->{"value"});
			
			$user_subscription = getUserSubscription($id);
			
			if( (strcmp($payment_status, "Completed") === 0) && ($payment_gross === 9.98) )
			{	
				insertHistoryNote("PAYMENT", $data_text, $id);
				
				setUserStatus($id, $detail, true);
				
				$data = json_encode(array("status" => ACTIVE));
			}
		}
	}
	header("HTTP/1.1 200 OK");				
?>