<?php

require 'dbconnect.inc.php';
$conn = OpenCon();

if (isset($_POST['logout-submit'])) {

	//Ends the user session and effectively logs them out
	session_start();
    $_SESSION = array();
    session_destroy();

	header('Location: ../index.php');
	exit();
}
else{
	header('Location: ../index.php');
	exit();
}