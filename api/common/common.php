<?php
	session_start();
	include_once __DIR__ . "/constants.php";
	include_once __DIR__ . "/config.php";
	include_once __DIR__ . "/util.php";
	include_once __DIR__ . "/http.php";
	include_once( __DIR__ . '/../../lib/datatables/scripts/ssp.class.php' );
	
	function listEntities($table, $primaryKey, $columns){

		$sql_details = array(
						'user' => 'dbenteris',
						'pass' => 'XXXXXXXXXXX-HIDDEN-XXXXXXXXXXX',
						'db'   => 'dbenteris',
						'host' => '188.121.57.62'
					);
		
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns)
		);
	}
	
	function getUserByEmail($email){
	
		$url = DB_API_BASE_URL . "/user?_view=json&_filter=" . urlencode("email==".$email);		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		return $response;
	}
	
	function getSubscription($id){
	
		$url = DB_API_BASE_URL . "/subscription";
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
										
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
		}
	}
	
	function getUserSubscription($user_id){
		$url = DB_API_BASE_URL . "/user_has_subscription?_view=json&_filter=" . urlencode("user_id==".$user_id);		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			
			return $rows[0];
		}
		
		return NULL;
	}
	
	function checkFeatureElement($feature_element){
		
		if(!isset($_SESSION["featureElements"])){
			return false;
		}
		
		$feature_elements = $_SESSION["featureElements"];
		
		return in_array($feature_element, $feature_elements);
	}
	
	function setUserStatus($id, $email, $status){
	
		$data = json_encode(array("status" => (($status==true) ? ACTIVE : INACTIVE)));
				
		$url = DB_API_BASE_URL . "/user/".$id;
		
		$response = \Httpful\Request::put($url)->sendsJson()
										->addHeader("Accept", "application/json")
										->addHeader('Content-Type', 'x-www-form-urlencoded')
										->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
										->body("_data=".$data)->send();	

		$url = ENTERIS_API_BASE_URL . "/user/auth";
		$data = array(  "email" => $email,
						"apiKey" => ENTERIS_KEY,
						"state" => (($status==true) ? "TRUE" : "FALSE")
					 );
						
		$response = \Httpful\Request::post($url)->sendsJson()
											->addHeader("Accept", "application/json")
											->addHeader('Content-Type', 'application/json')
											->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
											->body(json_encode($data))->send();
	}
	
	function insertHistoryNote($type, $message, $user_id){
		
		$data = json_encode(array(	"type" => $type,
									"message" => $message,
									"createdon" => getTodayDate(),
									"user_id" => $user_id
								 )
		);
		
		$url = DB_API_BASE_URL . "/history";
		
		$response = HTTPRequester::HTTPPost($url, array("_data" => $data));
	}
	
	function logError($message, $user_id){
		$key = randomPassword(15);
		$reference = "Reference# " . $key ."\n\r";
		$time = "Occurred on " . getTodayDate()."\n\r";
		insertHistoryNote(HISTORY_ERROR, $reference.$time.urldecode($message), $user_id);
		return $key;
	}
	
	function getUserFeatureElements($user_id){
		
		$roles = Array();		
		$url = DB_API_BASE_URL . "/user_has_role?_view=json&_filter=" . urlencode("user_id==".$user_id);
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			foreach($rows as $index => $data){
				array_push($roles, $data->{"values"}->{"role_id"}->{"value"});
			}
		}
		
		$feature_elements = Array();
		
		foreach($roles as $key=>$role_id){
			
			$url = DB_API_BASE_URL . "/role_has_feature_element?_view=json&_filter=" . urlencode("role_id==".$role_id);
			
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) > 0){
				
				$rows = $response->{"restify"}->{"rows"};
				
				for($i = 0; $i < count($rows); $i++){
					$feature_element = $rows[$i]->{"values"}->{"feature_element_id"}->{"outReference"}->{"values"}->{"name"};
					array_push($feature_elements, $feature_element);
				}
				
				return($feature_elements);
			}
			else{
				return Array();
			}
		}
	}