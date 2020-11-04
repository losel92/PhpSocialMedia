<?php  
	require 'header.php';
?>
	<main>
			<?php 
				if (isset($_SESSION['userId'])) { //if the user is logged in it'll refer to the logged in content
					require 'loggedin.index.php';
				}
				else{ //If the user is logged out, it'll refer to the logged out content
					require 'loggedout.index.php';
				}
			?>
	</main>

<?php 
	require 'footer.php';
 ?>