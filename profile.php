<?php
require_once "header.php";

if (isset($_GET['user']) && isset($_SESSION['userId']) && is_numeric($_GET['user'])) {

	if ($_SESSION['userId'] == $_GET['user']) {
		header("Location: index.php");
	}

	require_once "includes/dbconnect.inc.php";
	$conn = OpenCon();

	$followed = false;

	//Searches for the given user
	if ($res = $conn->query("SELECT * FROM users WHERE user_id = {$conn->real_escape_string($_GET['user'])}")) {
		if ($res->num_rows) {
			while ($row = $res->fetch_assoc()) {

				if ($res2 = $conn->query("SELECT * FROM followers WHERE follower_id={$_SESSION['userId']} AND following_id={$_GET['user']}")) {
					//A result was found, which means this user is being followed
					if ($res2->num_rows) {
						$followed = true;
					}
					else {
						$followed = false;
					}

					?>
					<div id="profile-page-wrapper" userid="<?php echo $row['user_id'] ?>">
						<div class="profile-column">

							<!-- Includes the js needed for handling changes in the profile info and picture -->
							<script type="text/javascript" src="./scripts/profile.js"></script>

							<img src="<?php echo $row['cropped_picture'] ?>" class="profile-column-photo">
							<p class="profile-column-big"><?php echo $row['username']; ?></p>

							<div id="<?php if ($followed == true) { echo "user-following-logo"; } else { echo "user-follow-logo"; } ?>"></div>

							<div class="profile-column-agegender">
								<div class="row1">
									<p class="profile-column-age"><?php echo $row['age']; ?>yo</p>
								</div>
								<div class="row1">
									<p class="profile-column-gender"><?php echo strtoupper($row['gender'][0]); ?></p>
								</div>
							</div>
							
							<div class="profile-column-name-container">
								<div class="row1">
									<p class="profile-column-name1">First Name:</p>
									<p class="profile-column-name1">Last Name:</p>
								</div>
								<div class="row1">
									<p class="profile-column-name2"><?php echo $row['first_name']; ?></p>
									<p class="profile-column-name2"><?php echo $row['last_name']; ?></p>
								</div>
							</div>

							<p class="profile-column-email">
								<a href="mailto:<?php echo $row['email'] ?>" class="red-link">
									<?php echo $row['email']; ?>
								</a>
							</p>
						</div>

						<div class="profile-posts-div">
							<div style="margin-top: 20px"></div>
							<?php 
								require 'posts/userPosts.php';

								?> 
									<!-- Includes the js needed for handling posts -->
									<script type="text/javascript" src="./scripts/posts.js"></script> 
							
									<!-- Js for comments -->
									<script type="text/javascript" src="./scripts/comments.js"></script> 
								<?php
								getAllPostsForSingleUser($row['user_id']);
							?>
						</div>

					</div>
				<?php 

				}
			}
		} else { //User id passed not found
			echo "<h1>User not found</h1>";
		} 
	} else {
	echo "sql error";
	}
} else { //User not logged in or 'user' field not set
	header("Location: index.php");
}

require_once 'footer.php';
?>