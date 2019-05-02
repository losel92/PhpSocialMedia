<div class="form-wrapper" id="pic-form-popup-wrapper">
<form class="form-popup" id="select-pic-form" method="post" action="upload.php" enctype="multipart/form-data">
		<h1><span>Upload a new profile picture!</span></h1>
		<h3><span>OBS: The file must be either .png or .jpg and 600x600</span></h3>
		<input type="file" name="profile_pic">
		<input type="submit">
</form>

<button onclick="hideForm('pic-form-popup-wrapper')">Cancel</button>

</div>

<div id="profile-page-wrapper">
	<div class="profile-column">
		<img src="<?php echo $_SESSION['profilePic'] ?>" class="profile-column-photo" onclick="showForm('pic-form-popup-wrapper')">
		<p class="profile-column-big"><?php echo $_SESSION['username']; ?></p>

		<div class="profile-column-name-container">
			<div class="row1">
				<p class="profile-column-name1">First Name:</p>
				<p class="profile-column-name1">Last Name:</p>
			</div>
			<div class="row1">
				<p class="profile-column-name2"><?php echo $_SESSION['firstName']; ?></p>
				<p class="profile-column-name2"><?php echo $_SESSION['lastName']; ?></p>
			</div>
		</div>

		<p class="profile-column-email">
			<a href="mailto:person@email.com" class="red-link">
				<?php echo $_SESSION['email']; ?>
			</a>
		</p>
	</div>

	<div class="profile-posts-div">
	</div>

</div>