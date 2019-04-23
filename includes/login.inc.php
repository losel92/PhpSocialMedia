<?php

if (isset($_POST['login-submit'])) {
	require 'dbconnect.inc.php';
	$conn = OpenCon();

	$mailUn = $_POST['usernamemail'];
	$pwd = $_POST['password'];

	//If the user leaves one of the fields empty, it returns an error
	if (empty($mailUn) || empty($pwd)) {
		header('Location: ../index.php?loginempty&un=' . $mailUn);
		exit();
	}
	else{
		//Tests the username and password against the ones from the database
		$sql = "SELECT * FROM users WHERE username=? OR email=?";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header('Location: ../index.php?error=sqlerror');
			exit();
		}
		else{
			mysqli_stmt_bind_param($stmt, "ss", $mailUn, $mailUn);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			//If the query returns a user, it checks to see if the password is correct
			if ($row = mysqli_fetch_assoc($result)) {
				$pwdCheck = password_verify($pwd, $row['pwd']);

				if($pwdCheck == false){ //If the password is wrong, throws an error
					header('Location: ../index.php?error=wrongpwd&un=' . $mailUn);
					exit();
				}
				else if($pwdCheck == true){ //Logs the user in, if the password is correct
					session_start();

					//saves all the user information to the session
					$_SESSION['userId'] = $row['user_id'];
					$_SESSION['username'] = $row['username'];
					$_SESSION['email'] = $row['email'];
					$_SESSION['firstName'] = $row['first_name'];
					$_SESSION['lastName'] = $row['last_name'];
					$_SESSION['gender'] = $row['gender'];
					$_SESSION['birthday'] = $row['birthday'];
					$_SESSION['age'] = $row['age'];
					$_SESSION['phoneNumber'] = $row['phone_number'];

					header('Location: ../index.php?login=success');
					exit();
				}
				else{
					header('Location: ../index.php?error=wrongpwd');
					exit();
				}
			}
			else{
				header('Location: ../index.php?error=nouser');
				exit();
			}
		}
	}
}
else{
	header('Location: ../index.php');
	exit();
}