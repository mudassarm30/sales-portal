<?php

	session_start();
	
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
			
	$email = null;
	$password = null;
	
	if(isset($_POST["email"])){
		$email =  $_POST["email"];
	}
	else{
		throwError("Email not provided", 400);
	}
	
	$url = DB_API_BASE_URL . "/user?_view=json&_filter=" . urlencode("email==".$email);
	$response = HTTPRequester::HTTPGet($url, array());	
	$response = json_decode($response);
	
	if(intval($response->{"restify"}->{"rowCount"}) === 0){
		throwError("User does not exist", 404);
	}
	
	$rows = $response->{"restify"}->{"rows"};
	$data = $rows[0];
	
	$db_email = $data->{"values"}->{"email"}->{"value"};
	$user_id = $data->{"values"}->{"id"}->{"value"};
	$db_pass  = base64_decode($data->{"values"}->{"password"}->{"value"});
	$fullname = $data->{"values"}->{"firstname"}->{"value"} . " " . $data->{"values"}->{"lastname"}->{"value"};
	
	if(true /*check if user have feature element 'Reset Password' */){
		
	}
	
	$password = randomPassword(RANDOM_PASSWORD_LENGTH);

	$url = DB_API_BASE_URL . "/user/" . $user_id;
					
	$data = json_encode(array("password" => base64_encode($password)));
							
	$response = \Httpful\Request::put($url)->sendsJson()
										->addHeader("Accept", "application/json")
										->addHeader('Content-Type', 'x-www-form-urlencoded')
										->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
										->body("_data=".$data)->send();	
	
	insertHistoryNote(HISTORY_PASSWORD_UPDATED, "User '".$$fullname."' updated his/her password", $user_id);
	
	$url = ENTERIS_API_BASE_URL . "/user/change/password?email=".urlencode($db_email)."&oldpass=".$db_pass."&password=".$password;
		
	$data = array();
					
	$response = \Httpful\Request::post($url)->sendsJson()
										->addHeader("Accept", "application/json")
										->addHeader('Content-Type', 'application/json')
										->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
										->body(json_encode($data))->send();
										
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