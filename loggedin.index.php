<div class="form-wrapper" id="pic-form-popup-wrapper">
	<div class="form-contents" id="pic-form-popup-contents">
		<form class="form-popup" id="select-pic-form" method="post" action="" enctype="multipart/form-data">
		    <span class="closeX" onclick="CloseModal('.form-contents')">&times;</span>
		    <h1>Upload a new profile picture!</h1>
		    <h3>OBS: The cropping feature works better if your image is 1:1</h3>
		    <input type="file" name="imgfile" id="imgfile" />
		    <label for="imgfile">Choose a file</label>
			<input type="button" name="upload" value="UPLOAD" id="profile-pic-upload-btn" class="btn">
		</form>

		<div class="img-preview">
		    <img src="<?php echo $_SESSION['profilePic']; ?>" id="img-crop-prev">
		</div>
		<div> 
			<form action="image-crop.php" method="post">
				<input type="hidden" id="x" name="x" />
				<input type="hidden" id="y" name="y" />
				<input type="hidden" id="w" name="w" />
				<input type="hidden" id="h" name="h" />
			</form>
			<button id="img-crop-btn" class="btn">CROP</button>
		</div>
	</div>
</div>
<div id="profile-page-wrapper">
	<div class="profile-column">
		<div class="profile-img-hov" onclick="OpenModal('.form-contents')"><h1>Change Profile Picture</h1></div>
		<img src="<?php echo $_SESSION['croppedPic'] ?>" class="profile-column-photo">
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
			<a href="mailto:<?php echo $_SESSION['email'] ?>" class="red-link">
				<?php echo $_SESSION['email']; ?>
			</a>
		</p>
	</div>

	<div class="profile-posts-div">

		<!-- Remember: only if the user is looking at their own profile page -->
		<!-- Make a button for it to open and close -->
		<div class="profile-post-creator-wrapper">
			<div class="post-creator-top"><span class="post-creator-title">Write a new post!</span></div>
			<div class="profile-post-creator-content">
				<form action="" method="POST">
					<input type="text" class="post-creator-head" placeholder="Give your post a title...">
					<textarea rows="5" class="post-creator-content" placeholder="Write your post here..."></textarea>
				</form>
			</div>
			<div class="post-creator-bottom"><span class="post-creator-btn">Post!</span></div>
		</div>

		<?php 
			require 'userPosts.php'; 
			getSinglePost($_SESSION['username'], '01/06/2019', 158, 'My Little Post', 'bla bla bla bla bla', '');
		?>
	</div>

</div>