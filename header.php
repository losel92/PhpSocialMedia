<?php  
	session_start(); //Starts the session if a user is logged in
?>
<!DOCTYPE html>
<html>
<head>
	<title>Fictional Website</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

	<header>
			<a href="index.php">
				<div id="imglogo"></div>
			</a>

			<ul class="header-ul">
				<li><a href="index.php">Home</a></li>
				<li><a href="#">About Us</a></li>
				<li><a href="#">Contact</a></li>
			</ul>


			<div class="login-wrapper">

				<?php if(!isset($_SESSION['userId'])){ //If there is no user logged in, it will show the login form and the signup button ?>

					<form action="includes/login.inc.php" method="post" id="login-form">
						<input type="text" name="usernamemail" placeholder="username/email">
						<input type="password" name="password" placeholder="password">
						<button type="submit" name="login-submit" id="login-button" class="btn">LOGIN</button>
					</form>

					<button onclick="location.href = 'signup.php';" id="signup-button" class="btn">SIGNUP</button>

				<?php } else{ //if there is a user logged in it will display the logout button ?>

					<form action="includes/logout.inc.php" method="post">
						<button type="submit" name="logout-submit" class="btn">LOGOUT</button>
					</form>

				<?php } //ends the else ?>

			</div>
	</header>