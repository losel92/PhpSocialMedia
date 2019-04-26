<?php //Grabs back information from the url when a signup error is detected and returns an error code

//Setting all of the variables here to an empty string so that we dont get 
//an error message inside the textboxes, ie. "Undefined variable at line x"
$username = '';
$firstName = '';
$lastName = '';
$email = '';
$tel = '';
$birthday = '';
$gender = '';

$error = '';
$errorMsg = '';

if (isset($_GET['error'])) {
	$error = $_GET['error'];

	//Login errors
	if ($error == 'nouser') {
		$errorMsg = 'Username not Found';
		echo '<style>input[name=usernamemail]{background-color: #ff9999;}</style>';
	}
	else if ($error == 'wrongpwd') {
		$errorMsg = 'Wrong Password';
    	echo '<style>input[name=loginpwd]{background-color: #ff9999;}</style>';
	}

	//Signup errors
    else if ($error != 'sqlerror') { //When an sql error is detected, it by default doesn't send any information back
    	$username = $_GET['un'];
    	$firstName = $_GET['fn'];
    	$lastName = $_GET['ln'];
    	$email = $_GET['mail'];
    	$tel = $_GET['tel'];
    	$birthday = $_GET['bd'];
    	$gender = $_GET['gender'];

    	switch ($error) { //Sends back a formatted error message to be displayed on the signup form
    		case 'invalidcharacters':
    			$errorMsg = 'Invalid Name(s) / Username';
    			echo '<style>.column input[type=text]{background-color: #ff9999;}</style>';
    			break;
    		
    		case 'invalidtel':
    			$errorMsg = 'Invalid Phone Number';
    			echo '<style>.column input[name=tel]{background-color: #ff9999;}</style>';
    			break;

    		case 'shortpwd':
    			$errorMsg = 'Password Must Be at Least 8 Characters Long';
    			echo '<style>.column input[name=pwd]{background-color: #ff9999;}</style>';
    			break;

    		case 'existingun':
    			$errorMsg = 'Username Taken';
    			echo '<style>.column input[name=username]{background-color: #ff9999;}</style>';
    			break;

    		case 'existingmail':
    			$errorMsg = 'Email Already in Use';
    			echo '<style>.column input[name=email]{background-color: #ff9999;}</style>';
    			break;

    		default:
    			$errorMsg = 'Sign up Error';
    			break;
    	}
    }
}
if (isset($_GET['signup'])) {
		$errorMsg = 'Signup Successful';
		echo '<style>#signup-error-msg{color: green;}</style>';
}
