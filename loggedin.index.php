<!-- The picture upload and crop popup form -->
<div class="form-wrapper popup-form-wrapper" id="pic-form-popup-wrapper">
	<div class="form-contents popup-form-contents" id="pic-form-popup-contents">
		<form class="form-popup" id="select-pic-form" method="post" action="" enctype="multipart/form-data">
		    <span class="closeX" id="pic-form-x" onclick="CloseModal('.form-contents')">&times;</span>
		    <h1>Upload a new profile picture!</h1>
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

<!-- Profile info edit popup -->
<div class="form-wrapper popup-form-wrapper">
	<div class="form-contents popup-form-contents" id="info-edit-form-container">
		<span class="closeX" id="info-edit-x" onclick="CloseModal('.form-contents')">&times;</span>
		<div class="flex" id="info-edit-name-flex">
			<input type="text" id="info-edit-fname" value="<?php echo $_SESSION['firstName']; ?>" placeholder="First Name" required>
			<input type="text" id="info-edit-lname" value="<?php echo $_SESSION['lastName']; ?>" placeholder="Last Name" required>
		</div>
		<div class="flex" id="info-edit-mailbd-flex">
			<input type="text" id="info-edit-email" value="<?php echo $_SESSION['email']; ?>" placeholder="Email" required>
			<input type="date" id="info-edit-bday" <?php echo 'max="' . date('Y') . '-' . date('m') . '-' . date('d') . '"'; ?> value="<?php echo $_SESSION['birthday']; ?>" placeholder="Birthday" required>
		</div>
		<button id="info-edit-submit">Edit</button>
	</div>
</div>

<div id="profile-page-wrapper">
	<div class="profile-column">

		<!-- Includes the js needed for handling changes in the profile info and picture -->
		<script type="text/javascript" src="./scripts/profile.js"></script>

		<div class="profile-img-hov" onclick="OpenModal('#pic-form-popup-contents')"><h1>Change Profile Picture</h1></div>
		<img src="<?php echo $_SESSION['croppedPic'] ?>" class="profile-column-photo">
		<p class="profile-column-big"><?php echo $_SESSION['username']; ?></p>

		<div id="profile-edit-logo"></div>

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
		<div class="profile-post-creator-wrapper">
			<div class="post-creator-top"><span class="post-creator-title">Write a new post!</span></div>
			<div class="profile-post-creator-content">
				<form method="POST" action="./posts/createPost.inc.php">
					<input type="text" class="post-creator-head" placeholder="Give your post a title...">
					<textarea rows="5" class="post-creator-content" placeholder="Write your post here..."></textarea>
			</div>
					<div class="post-creator-bottom"><button type="submit" class="post-creator-btn">Post!</button></div>
					<p id="post-creator-load" style="margin: 0;"></p>
				</form>
		</div>

		<?php 
			//Opens a connection to the database
			require 'includes/dbconnect.inc.php';
			$conn = OpenCon();

			require './posts/userPosts.php';

			?> <script type="text/javascript" src="./scripts/posts.js"></script> <?php //Includes the js needed for handling posts
			
			$sql = "SELECT * FROM user_posts WHERE user_id=$_SESSION[userId] ORDER BY post_timestamp DESC";
			if($result = mysqli_query($conn, $sql)){
				//If the user has posted anything // ie. if the query returns any values
				if (mysqli_num_rows($result) > 0) {
					//The loop that shows all the posts
					while($row = mysqli_fetch_assoc($result)) {
						getSinglePost($row['post_id'] ,$row['username'], date('d/m/Y',$row['post_timestamp']), $row['likes'], $row['head'], $row['content'], $row['edit_timestamp']);
					}
				}
				//If the user hasn't posted anything yet
				else{
					echo "Your posts will appear here!";
				}
			}
			//There was an error
			else{
				?><script>console.log("SQL Error");</script><?php
			}
		?>
	</div>

</div>