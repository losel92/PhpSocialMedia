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

			<?php if(isset($_GET['error']) || isset($_GET['signup'])){ ?>
				<h3 id="signup-error-msg"><?php echo $errorMsg ?></h3>
			<?php } ?>

			<div class="row">
				<div class="column">
					<input type="text" name="firstname" placeholder="First Name" value="<?php echo $firstName; ?>" required>
					<input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>" required>
					<input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required>
					Birthday: <input type="date" name="birthday" id="signup-birthday" <?php echo 'max="' . date('Y') . '-' . date('m') . '-' . date('d') . '"'; ?> value="<?php echo $birthday; ?>" required>
				</div>

				<div class="column">
					<input type="text" name="lastname" placeholder="Last Name" value="<?php echo $lastName; ?>" required>
					<input type="password" name="pwd" placeholder="Password" required>
					<input type="tel" name="tel" placeholder="Phone Number" value="<?php echo $tel; ?>" required>
					
					<div id="signup-gender">
						<input type="radio" name="gender" value="male" <?php if($gender == 'male') echo 'checked'; ?> required> Male </input>
						<input type="radio" name="gender" value="female" <?php if($gender == 'female') echo 'checked'; ?> required> Female </input>
					</div>
				</div>
			</div>

			
			

			<button type="submit" name="signup-submit" class="btn" id="signup-btn">SIGNUP</button>
		</form>
	</div>

</main>

<div style="height: 700px; width: 100%;"></div>
<?php require 'footer.php'; ?>