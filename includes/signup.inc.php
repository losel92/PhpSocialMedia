<?php
if(isset($_POST['signup-submit'])){

	//Opens a connection to the database
	require 'dbconnect.inc.php';
	$conn = OpenCon();

	//Gets all the user input
	$username = $_POST['username'];
	$first_name = $_POST['firstname'];
	$last_name = $_POST['lastname'];
	$email = $_POST['email'];
	$pwd = $_POST['pwd'];
	$tel = $_POST['tel'];
	$birthday = $_POST['birthday'];
	$gender = $_POST['gender'];

	if ($gender == "male") { //Gives the user an avatar based on their gender
		$picture = "https://cdn.pixabay.com/photo/2014/04/03/10/32/businessman-310819__340.png";
	}
	else{
		$picture = "https://cdn.pixabay.com/photo/2014/04/03/10/32/user-310807__340.png";
	}
	
	//Checks if the username / first name / last name has any invalid characters
	if (!preg_match("/^[a-zA-Z0-9]*$/", $username) || !preg_match("/^[a-zA-Z]*$/", $first_name) || !preg_match("/^[a-zA-Z]*$/", $last_name)) {
		header('Location: ../signup.php?error=invalidcharacters&un='.$username.'&fn='.$first_name.'&ln='.$last_name.'&mail='.$email.'&tel='.$tel.'&bd='.$birthday.'&gender='.$gender);
		exit();
	}
	//Same thing but for the phone number
	else if (!preg_match("/^[0-9\-\+\s\)\(]*$/", $tel) || strlen($tel) < 7 || strlen($tel) > 25) {
		header('Location: ../signup.php?error=invalidtel&un='.$username.'&fn='.$first_name.'&ln='.$last_name.'&mail='.$email.'&tel='.$tel.'&bd='.$birthday.'&gender='.$gender);
		exit();
	}
	//Checks for a password that is shorter than 8 characters
	else if (strlen($pwd) < 8) {
		header('Location: ../signup.php?error=shortpwd&un='.$username.'&fn='.$first_name.'&ln='.$last_name.'&mail='.$email.'&tel='.$tel.'&bd='.$birthday.'&gender='.$gender);
		exit();
	}
	else{

		//Checks for a username that's already in use
		$sql = "SELECT * FROM users WHERE username=?";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../signup.php?error=sqlerror");
			exit();
		}
		else{
			mysqli_stmt_bind_param($stmt, "s", $username);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$rowCount = mysqli_stmt_num_rows($stmt);
			if ($rowCount > 0) {
				header('Location: ../signup.php?error=existingun&un='.$username.'&fn='.$first_name.'&ln='.$last_name.'&mail='.$email.'&tel='.$tel.'&bd='.$birthday.'&gender='.$gender);
				exit();
			}
			else{
				//Same thing but for the email
				$sql = "SELECT * FROM users WHERE email=?";
				$stmt = mysqli_stmt_init($conn);

				if (!mysqli_stmt_prepare($stmt, $sql)) {
					header("Location: ../signup.php?error=sqlerror");
					exit();
				}
				else{
					mysqli_stmt_bind_param($stmt, "s", $email);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_store_result($stmt);
					$rowCount = mysqli_stmt_num_rows($stmt);
					if ($rowCount > 0) {
						header('Location: ../signup.php?error=existingmail&un='.$username.'&fn='.$first_name.'&ln='.$last_name.'&mail='.$email.'&tel='.$tel.'&bd='.$birthday.'&gender='.$gender);
						exit();
					}
					else{
						//Inserts all the data into the database once the username and email are new and valid
						$sql = "INSERT INTO users (username, email, first_name, last_name, pwd, gender, birthday, phone_number, profile_picture) VALUES (?,?,?,?,?,?,?,?,?)";
						$stmt = mysqli_stmt_init($conn);
						if (!mysqli_stmt_prepare($stmt, $sql)) {
							header("Location: ../signup.php?error=sqlerror");
							exit();
						}
						else{
							$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

							mysqli_stmt_bind_param($stmt, "sssssssss", $username, $email, $first_name, $last_name, $hashedPwd, $gender, $birthday, $tel, $picture);
							mysqli_stmt_execute($stmt);

							header("Location: ../signup.php?signup=success");
							exit();
						}
					}
				}
			}
		}
	}
	//Closes the connection to the database
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}

//If the user simply inserts the url without completing the signup form they'll get redirected to index.php
else{
	header('Location: ../index.php');
	exit();
}