<?php

	session_start();

	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	
	session_unset();
	header("Location: " . THIS_SERVICE_BASE_URL . "/login.php");
?>