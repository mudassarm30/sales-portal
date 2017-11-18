<?php

	session_start();
	
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	
	if(!isset($_SESSION["loggedIn"]) || ($_SESSION["loggedIn"] !== true)){
		throwError("User not logged in", 400);
	}
	
	$user = $_SESSION["user"];
	$user_id = $user->{"id"}->{"value"};
			
	try{
		
		if(checkFeatureElement(FE_Update_Password)){
	
			$admin_user_id = null;
			$password = null;
			$old_password = null;
			$session_email = $user->{"email"}->{"value"};
			
			if(isset($_POST["user_id"])){
				$admin_user_id = $user_id;
				$user_id =  $_POST["user_id"];
			}
			
			if(isset($_POST["password"])){
				$password = $_POST["password"];
			}
			
			if(isset($_POST["old_password"])){
				$old_password = $_POST["old_password"];
			}
			
			$url = DB_API_BASE_URL . "/user?_view=json&_filter=" . urlencode("id==".$user_id);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) === 0){
				throwError("User does not exist", 404);
			}
			
			$rows = $response->{"restify"}->{"rows"};
			$data = $rows[0];
			
			$db_email = $data->{"values"}->{"email"}->{"value"};
			$db_pass  = base64_decode($data->{"values"}->{"password"}->{"value"});
			$fullname = $data->{"values"}->{"firstname"}->{"value"} . " " . $data->{"values"}->{"lastname"}->{"value"};
			
			if ((!checkFeatureElement(FE_Manage_Users)) && (strcmp($session_email, $db_email) !== 0)){
				
				throwError("Invalid request: you are trying to change password for someone else", 400);
			}
			
			// Check if user has 'Password Reset' feature element.
			
			if($old_password === null){
				$password = randomPassword(RANDOM_PASSWORD_LENGTH);
			}
			else if(strcmp($old_password, $db_pass) !== 0){
				throwError("Wrong old password is provided", 404);
			}
			
			insertHistoryNote(HISTORY_PASSWORD_UPDATED, "User '".$fullname."' updated his/her password", $user_id);
			
			$url = ENTERIS_API_BASE_URL . "/user/change/password?email=".urlencode($db_email)."&oldpass=".$db_pass."&password=".$password;
				
			$data = array();
							
			$response = \Httpful\Request::post($url)->sendsJson()
												->addHeader("Accept", "application/json")
												->addHeader('Content-Type', 'application/json')
												->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
												->body(json_encode($data))->send();
												
			$url = DB_API_BASE_URL . "/user/" . $user_id;
							
			$data = json_encode(array("password" => base64_encode($password)));
									
			$response = \Httpful\Request::put($url)->sendsJson()
												->addHeader("Accept", "application/json")
												->addHeader('Content-Type', 'x-www-form-urlencoded')
												->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
												->body("_data=".$data)->send();	
			
			

												
			$htmlContent = '
					<html>
					<head>
						<title>Originssoft Enteris</title>
					</head>
					<body>
						<h3>Your password is changed</h3>
						<table cellspacing="0" style="border: 2px dashed #FB4314; width: 300px; height: 200px;">
							<tr>
								<th align="left" style="padding-left: 10px">Name:</th><td>'.$fullname.'</td>
							</tr>
							<tr style="background-color: #e0e0e0;">
								<th align="left" style="padding-left: 10px">Email:</th><td>'.$db_email.'</td>
							</tr>
							<tr>
								<th align="left" style="padding-left: 10px">Password:</th><td>'.$password.'</td>
							</tr>
							<tr style="background-color: #e0e0e0;">
								<th align="left" style="padding-left: 10px">Website:</th><td><a href="http://www.originssoft.com/enteris/private">www.originssoft.com/enteris/private</a></td>
							</tr>
						</table>
					</body>
					</html>';
			
			sendEmail($db_email, "Enteris Password Changed", ENTERIS_ADMIN_NAME, ENTERIS_ADMIN_EMAIL, $htmlContent, $cc=NULL, $bcc=NULL);
			
			echo $response;
		
		}
		else{
			echo OPERATION_NOT_ALLOWED;
		}
	
	} 
	catch (Exception $e) {
		$key = logError($e->getMessage(), $user_id);
		throwError("Operation could not be performed, please contact the administrator, provide reference number as " . $key, 500);
	}