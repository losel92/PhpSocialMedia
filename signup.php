<main>
	<div id="signup-content" class="popup-form" style="display: none;">
		<div id="signup-wrapper">
			<form method="POST" action="includes/signup.inc.php" id="signup-form" class="popup-form">
				<span class="closeX" onclick="CloseModal('#signup-wrapper')">&times;</span>
				<h1 id="signup-title">Sign Up</h1>

				<h3 id="signup-error-msg"></h3>

				<div class="row">
					<div class="column">
						<input type="text" id="signup-fname" name="firstname" placeholder="First Name" value="<?php echo $firstName; ?>" tabindex="1" required>
						<input type="text" id="signup-uname" name="username" placeholder="Username" value="<?php echo $username; ?>" tabindex="3" required>
						<input type="email" id="signup-email" name="email" placeholder="Email" value="<?php echo $email; ?>" tabindex="5" required>
						Birthday: <input  type="date" name="birthday" id="signup-birthday" <?php echo 'max="' . date('Y') . '-' . date('m') . '-' . date('d') . '"'; ?> value="<?php echo $birthday; ?>" tabindex="7" required>
					</div>

					<div class="column">
						<input type="text" id="signup-lname" name="lastname" placeholder="Last Name" value="<?php echo $lastName; ?>" tabindex="2" required>
						<input type="password" id="signup-pwd" name="pwd" placeholder="Password" tabindex="4" required>
						<input type="tel" id="signup-tel" name="tel" placeholder="Phone Number" value="<?php echo $tel; ?>" tabindex="6" required>
						
						<select id="signup-gender" tabindex="8">
							<option value="male" <?php if($gender == 'male') echo 'checked'; ?> required> Male </option>
							<option value="female" <?php if($gender == 'female') echo 'checked'; ?> required> Female </option>
						</select>
					</div>
				</div>
				<button type="submit" name="submit" class="btn" id="signup-btn">SIGNUP</button>
				<p class="pload"></p>
			</form>
		</div>
	</div>

</main>

<div style="height: 700px; width: 100%;"></div>
<?php //require 'footer.php'; ?>