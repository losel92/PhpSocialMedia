<?php
if(isset($_POST['username'])){

	//Opens a connection to the database
	require 'dbconnect.inc.php';
	$conn = OpenCon();

	require 'functions.inc.php';

	//Gets all the user input
	$username = $_POST['username'];
	$first_name = $_POST['firstname'];
	$last_name = $_POST['lastname'];
	$email = $_POST['email'];
	$pwd = $_POST['pwd'];
	$tel = $_POST['tel'];
	$birthday = $_POST['birthday'];
	$gender = $_POST['gender'];
	$age = CalculateAge($birthday);

	if ($gender == "male") { //Gives the user an avatar based on their gender
		$picture = "https://cdn.pixabay.com/photo/2014/04/03/10/32/businessman-310819__340.png";
	}
	else{
		$picture = "https://cdn.pixabay.com/photo/2014/04/03/10/32/user-310807__340.png";
	}
	
	//Checks if the username / first name / last name has any invalid characters
	if (!preg_match("/^[a-zA-Z0-9]*$/", $username) || !preg_match("/^[a-zA-Z]*$/", $first_name) || !preg_match("/^[a-zA-Z]*$/", $last_name)) {
		$msg = '<span class="form-error">Invalid Characters</span>';
		$errorCode = 'invalidcharacters';
	}
	//Same thing but for the phone number
	else if (!preg_match("/^[0-9\-\+\s\)\(]*$/", $tel) || strlen($tel) < 7 || strlen($tel) > 25) {
		$msg = '<span class="form-error">Invalid Phone Number</span>';
		$errorCode = 'invalidtel';
	}
	//Checks for a password that is shorter than 8 characters
	else if (strlen($pwd) < 8) {
		$msg = '<span class="form-error">Password must be at least 8 characters long</span>';
		$errorCode = 'shortpwd';
	}
	else{

		//Checks for a username that's already in use
		$sql = "SELECT * FROM users WHERE username=?";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			$msg = '<span class="form-error">SQL Error</span>';
			$errorCode = 'sqlerror';
		}
		else{
			mysqli_stmt_bind_param($stmt, "s", $username);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$rowCount = mysqli_stmt_num_rows($stmt);
			if ($rowCount > 0) {
				$msg = '<span class="form-error">Username taken</span>';
				$errorCode = 'existingun';
			}
			else{
				//Same thing but for the email
				$sql = "SELECT * FROM users WHERE email=?";
				$stmt = mysqli_stmt_init($conn);

				if (!mysqli_stmt_prepare($stmt, $sql)) {
					$msg = '<span class="form-error">SQL Error</span>';
					$errorCode = 'sqlerror';
				}
				else{
					mysqli_stmt_bind_param($stmt, "s", $email);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_store_result($stmt);
					$rowCount = mysqli_stmt_num_rows($stmt);
					if ($rowCount > 0) {
						$msg = '<span class="form-error">Email taken</span>';
						$errorCode = 'existingmail';
					}
					else{
						//Inserts all the data into the database once the username and email are new and valid
						$sql = "INSERT INTO users (username, email, first_name, last_name, pwd, gender, birthday, age, phone_number, profile_picture, cropped_picture) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
						$stmt = mysqli_stmt_init($conn);
						if (!mysqli_stmt_prepare($stmt, $sql)) {
							$msg = '<span class="form-error">SQL Error</span>';
							$errorCode = 'sqlerror';
						}
						else{
							$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

							mysqli_stmt_bind_param($stmt, "sssssssisss", $username, $email, $first_name, $last_name, $hashedPwd, $gender, $birthday, $age, $tel, $picture, $picture);
							mysqli_stmt_execute($stmt);

							$msg = '<span class="form-success">Signup successful</span>';
							$errorCode = 'noerror';

							//Logs the user in
							$sql = "SELECT * FROM users WHERE username = '$username'";
							$result = mysqli_query($conn, $sql);
							$row = mysqli_fetch_assoc($result);

							//saves all the user information to the session
							session_start();
							$_SESSION['userId'] = $row['user_id'];
							$_SESSION['username'] = $row['username'];
							$_SESSION['email'] = $row['email'];
							$_SESSION['firstName'] = $row['first_name'];
							$_SESSION['lastName'] = $row['last_name'];
							$_SESSION['gender'] = $row['gender'];
							$_SESSION['birthday'] = $row['birthday'];
							$_SESSION['phoneNumber'] = $row['phone_number'];
							$_SESSION['profilePic'] = $row['profile_picture'];
							$_SESSION['croppedPic'] = $row['cropped_picture'];
							$_SESSION['age'] = $age;

						}
					}
				}
			}
		}
	}
}

//If the user simply inserts the url without completing the signup form they'll get redirected to index.php
else{
	$msg = 'You fucked up somewhere Losel';
}

?>

<script>

	//Gets the error code from the above php script
	var errorCode = "<?php echo $errorCode ?>";

	//Gets the msg
	var msg = '<?php echo $msg ?>';

	//If the signup was successful, it will reload the page so the user gets logged in immediately
	if(errorCode == 'noerror') { 
		location.reload(); 
	}
	//If there was an error
	else{
		//Changes the error/success message
		$('#signup-error-msg').html(msg);

		//Resets the border-color of the inputs
		$('#signup-fname, #signup-lname, #signup-uname, #signup-pwd, #signup-email, #signup-tel').css('border-color', 'black');

		//Resets the password input field
		$('#signup-pwd').val('');

		//Changes the border-color of the inputs that contain an error
		switch (errorCode) {
			case 'invalidcharacters':
				$('#signup-fname, #signup-lname, #signup-uname').css('border-color', 'red');
				break;
			
			case 'invalidtel':
				$('#signup-tel').css('border-color', 'red');
				break;

			case 'shortpwd':
				$('#signup-pwd').css('border-color', 'red');
				break;

			case 'existingun':
				$('#signup-uname').css('border-color', 'red');
				break;

			case 'existingmail':
				$('#signup-email').css('border-color', 'red');
				break;
		}
	}


</script>