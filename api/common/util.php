<?php

	function getTodayDate(){
		
		return date("Y-m-d H:i:s");
	}
	
	function getToday(){
		
		$timestamp = strtotime(getTodayDate()); 
		return(idate('d', $timestamp));
	}
	
	function throwError($message, $code){
		
		$desc = "";
		switch($code){
			case 404:
				$desc = "NOT FOUND";
			break;
			case 400:
				$desc = "BAD REQUEST";
			break;
			case 500:
				$desc = "SERVER INTERNAL ERROR";
			break;
		}
		
		header("HTTP/1.1 " . $code . " " . $desc);
		echo '{"data": "' . $message . '"}';
		exit();
	}
	
	function randomPassword($length) {
		
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < $length; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}

	function getGUID(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
				.substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12)
				.chr(125);// "}"
			return $uuid;
		}
	}
	
	function sendEmail($to, $subject, $senderName, $senderEmail, $htmlContent, $cc=NULL, $bcc=NULL){

		// Set content-type header for sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// Additional headers
		$headers .= 'From: '.$senderName.'<'.$senderEmail.'>' . "\r\n";
		
		if($cc !== NULL)
			$headers .= 'Cc: ' . $cc . "\r\n";
		
		if($bcc !== NULL)
			$headers .= 'Bcc: ' . $bcc . "\r\n";

		// Send email
		return mail($to,$subject,$htmlContent,$headers);
	}
?>