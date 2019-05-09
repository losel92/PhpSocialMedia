<?php

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
     echo $path; //Returns the path to the now uploaded img
   }else{
     echo 0; //returns an error - not valid file type
   }
}