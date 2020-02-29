<?php 

//Starts the session if a user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Fictional Website</title>

	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="AdditionalStuff/jquery.Jcrop.min.css">
	<link rel="stylesheet" type="text/css" href="./content/style.css">
	<link rel="stylesheet" type="text/css" href="./content/comment-section.css">

	<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>

	<script type="text/javascript" src="AdditionalStuff/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="AdditionalStuff/jquery.Jcrop.min.js"></script>
	
	<script type="text/javascript" src="script.js"></script>

	<!-- General global js functions -->
	<script type="text/javascript" src="./scripts/general.js"></script> 
</head>
<body>
	<?php include 'includes/signuperrors.inc.php'; ?>
	<header>
			<a href="index.php">
				<div id="imglogo"></div>
			</a>

			<ul class="header-ul">
				<li id="header-profile"><a href="index.php">Profile</a></li>
				<li id="header-feed"><a href="#">Feed</a></li>
				<li id="header-search"><a href="loggedin.searchResults.php">Search</a></li>
			</ul>


			<div class="login-wrapper">

				<?php if(!isset($_SESSION['userId'])){ //If there is no user logged in ?>

					<!-- Includes the js needed for handling signup -->
					<script type="text/javascript" src="./scripts/signup.js"></script>

					<form action="includes/login.inc.php" method="post" id="login-form">
						<input type="text" name="usernamemail" placeholder="<?php if($error == 'nouser'){echo $errorMsg;} else{echo 'username/email';} ?>" value="<?php echo $username ?>">
						<input type="password" name="loginpwd" placeholder="<?php if($error == 'wrongpwd'){echo $errorMsg;} else{echo 'Password';} ?>">
						<button type="submit" name="login-submit" id="login-button" class="btn">LOGIN</button>
					</form>

					<?php require 'signup.php'; ?>

					<button onclick="OpenModal('#signup-wrapper');" id="signup-button" class="btn">SIGNUP</button>

				<?php } else{ //if there is a user logged in ?>

					<!-- Username besides little pic at the top -->
					<a href="#"><div id="un-top-right"><?php echo $_SESSION['username']; ?></div></a>
					<!-- Little pic at the top right -->
					<a href="#"><div id="pic-top-right" style="background-image: url(<?php echo $_SESSION['croppedPic']; ?>);"></div></a>

					<form action="includes/logout.inc.php" method="post">
						<button type="submit" name="logout-submit" class="btn">LOGOUT</button>
					</form>

				<?php } //ends the else ?>

			</div>
	</header>