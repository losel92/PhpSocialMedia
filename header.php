<?php  
	session_start(); //Starts the session if a user is logged in

	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Fictional Website</title>
	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="AdditionalStuff/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="script.js"></script>
</head>
<body>
	<?php include 'includes/signuperrors.inc.php'; ?>
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

				<?php if(!isset($_SESSION['userId'])){ //If there is no user logged in ?>

					<form action="includes/login.inc.php" method="post" id="login-form">
						<input type="text" name="usernamemail" placeholder="<?php if($error == 'nouser'){echo $errorMsg;} else{echo 'username/email';} ?>" value="<?php echo $username ?>">
						<input type="password" name="loginpwd" placeholder="<?php if($error == 'wrongpwd'){echo $errorMsg;} else{echo 'Password';} ?>">
						<button type="submit" name="login-submit" id="login-button" class="btn">LOGIN</button>
					</form>

					<button onclick="location.href = 'signup.php';" id="signup-button" class="btn">SIGNUP</button>

				<?php } else{ //if there is a user logged in ?>

					<!-- Username besides little pic at the top -->
					<a href="#"><div id="un-top-right"><?php echo $_SESSION['username']; ?></div></a>
					<!-- Little pic at the top right -->
					<a href="#"><div id="pic-top-right" style="background-image: url(<?php echo $_SESSION['profilePic']; ?>);"></div></a>

					<form action="includes/logout.inc.php" method="post">
						<button type="submit" name="logout-submit" class="btn">LOGOUT</button>
					</form>

				<?php } //ends the else ?>

			</div>
	</header>