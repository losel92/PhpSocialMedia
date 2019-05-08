<div class="form-wrapper" id="pic-form-popup-wrapper">
	<div class="form-contents" id="pic-form-popup-contents">
		<form class="form-popup" id="select-pic-form" method="post" action="upload.php" enctype="multipart/form-data">
		    <span class="closeX">&times;</span>
		    <h1>Upload a new profile picture!</h1>
		    <h3>OBS: The file must be either .png or .jpg and 600x600</h3>
		    <input type="file" name="profile_pic">
			<input type="submit">
		</form>

		<button onclick="hideForm('id', 'pic-form-popup-wrapper')">Cancel</button>
	</div>
</div>
</div>

<div id="profile-page-wrapper">
	<div class="profile-column">
		<div class="profile-img-hov" onclick="showForm('id', 'pic-form-popup-wrapper')"><h1>Change Profile Picture</h1></div>
		<img src="<?php echo $_SESSION['profilePic'] ?>" class="profile-column-photo">
		<p class="profile-column-big"><?php echo $_SESSION['username']; ?></p>

		<div class="profile-column-agegender">
			<div class="row1">
				<p class="profile-column-age"><?php echo $_SESSION['age']; ?>yo</p>
			</div>
			<div class="row1">
				<p class="profile-column-gender"><?php echo strtoupper($_SESSION['gender'][0]); ?></p>
			</div>
		</div>
		
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