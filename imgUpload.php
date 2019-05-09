<?php
//Connects to the db
require 'includes/dbconnect.inc.php';
$conn = OpenCon();
session_start();

if(isset($_FILES['file']['name'])){ //Just a normal img upload via the file input
	// Gets the file name
	$filename = $_FILES['file']['name'];

	//Gets the path of the img being uploaded
	$path = "uploads/".$filename;
	$valid = 1;
	$fileType = pathinfo($path,PATHINFO_EXTENSION);

	// Valid img extensions
	$validTypes = array("jpg","jpeg","png");

	//Checks for a valid extention
	if(!in_array(strtolower($fileType), $validTypes)) { //if the file is not an image
	   $valid = 0;
	}

	if($valid == 0){
	   echo 0; //returns an error - not valid file type
	}
	else{
		if(move_uploaded_file($_FILES['file']['tmp_name'],$path)){ //Uploads the img
			//Updates the user session
			$_SESSION['profilePic'] = $path;

			//Updates the db
			$sql = "UPDATE users SET profile_picture ='$path' WHERE user_id = $_SESSION[userId]";
			mysqli_query($conn, $sql);
	   		echo $path; //Returns the path to the now uploaded img
	   }else{
	     echo 0; //returns an error - not valid file type
	   }
	}
}
else if (isset($_POST['croppedPath'])) { //It's asking us to upload a cropped img into the db
	$path = $_POST['croppedPath'];

	//Updates the user session
	$_SESSION['croppedPic'] = $path;

	//Updates the db
	$sql = "UPDATE users SET cropped_picture ='$path' WHERE user_id = $_SESSION[userId]";
	mysqli_query($conn, $sql);
}
else{
	header('Location: index.php');
}