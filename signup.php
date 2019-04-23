<?php

//Connects to db
include 'includes/dbconnect.inc.php';
$conn = OpenCon();

//gets the header
require 'header.php';

include 'includes/signuperrors.inc.php';
?>


<main>
	<div id="signup-wrapper">
		<form action="includes/signup.inc.php" method="post" id="signup-form">
			<h1 id="signup-title">Sign Up</h1>
			<div class="row">
				<div class="column">
					<input type="text" name="firstname" placeholder="First Name" required>
					<input type="text" name="username" placeholder="Username" required>
					<input type="email" name="email" placeholder="Email" required>
					Birthday: <input type="date" name="birthday" id="signup-birthday" <?php echo 'max="' . date('Y') . '-' . date('m') . '-' . date('d') . '"'; ?> required>
				</div>

				<div class="column">
					<input type="text" name="lastname" placeholder="Last Name" required>
					<input type="password" name="pwd" placeholder="Password" required>
					<input type="tel" name="tel" placeholder="Phone Number" required>
					
					<div id="signup-gender">
						<input type="radio" name="gender" value="male" required> Male </input>
						<input type="radio" name="gender" value="female" required> Female </input>
					</div>
				</div>
			</div>

			
			

			<button type="submit" name="signup-submit" class="btn" id="signup-btn">SIGNUP</button>
		</form>
	</div>

</main>


<?php require 'footer.php'; ?>