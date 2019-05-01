<div id="profile-page-wrapper">

	<div class="profile-column">
		<img src="<?php echo $_SESSION['profilePic'] ?>" class="profile-column-photo">
		<p class="profile-column-username"><?php echo $_SESSION['username'] ?></p>
		<div class="profile-column-name-container">
			<div class="row1">
				<p class="profile-column-name1">First Name:</p>
				<p class="profile-column-name1">Last Name:</p>
			</div>
			<div class="row1">
				<p class="profile-column-name2"><?php echo $_SESSION['firstName'] ?></p>
				<p class="profile-column-name2"><?php echo $_SESSION['lastName'] ?></p>
			</div>
		</div>
	</div>

	<div class="profile-posts-div">
	</div>

</div>