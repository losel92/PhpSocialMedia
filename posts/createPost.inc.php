<?php
    if(isset($_POST['postContent'])){

        //Opens a connection to the database and starts the session
        require '../includes/dbconnect.inc.php';
        $conn = OpenCon();
        session_start();

        //gets the data to be inserted into the database
        $postHead = $_POST['postHead'];
        $postContent = $_POST['postContent'];

        $userId = $_SESSION['userId'];
        $username = $_SESSION['username'];
        $timestamp = time();
        $likes = 0;
        $edit_timestamp = 0;

        //Error handlers for empty post_head and post_content
        if($postHead == ""){
            $error = "emptyHead";
            $postId = '';
        }
        else if($postContent == ""){
            $error = "emptyContent";
            $postId = '';
        }
        else{
            //inserts the data into the database
            $sql = "INSERT INTO user_posts (user_id, username, post_timestamp, likes, head, content, edit_timestamp) VALUES (?,?,?,?,?,?,?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                $error = 'sqlError';
                $postId ='';
            }
            else{
                mysqli_stmt_bind_param($stmt, "isiissi", $userId, $username, $timestamp, $likes, $postHead, $postContent, $edit_timestamp);
                mysqli_stmt_execute($stmt);
                
                

                $sql = "SELECT * FROM user_posts WHERE user_id=$userId AND post_timestamp=$timestamp";
                if($result = mysqli_query($conn, $sql)){
                    $row = mysqli_fetch_row($result);
                    $postId = $row[0];

                    $error = 'noError';
                    require 'userPosts.php';
                }
                else{
                    $error = 'sqlError';
                    $postId = '';
                }
            }
        }


    }
    //If the user just typed in the url to this file, they'll be redirected to the initial page
    else{
        header('location: ../index.php');
        exit();  
    }


?>

<script>
    //Gets the error code from the above php script
    var errorCode = '<?php echo $error; ?>';

    //Resets the border of the inputs
    $('.post-creator-head, post-creator-content').css('border', '2px solid #3d3d3d');

    if(errorCode == "sqlError"){
        alert("SQL Error");
    }
    else if(errorCode == "emptyHead"){
        $('.post-creator-head').css('border', '2px solid red');
    }
    else if(errorCode == "emptyContent"){
        $('.post-creator-content').css('border', '2px solid red');
    }
    //If there were no errors
    else{
        //Resets the input fields
        $('.post-creator-head, .post-creator-content').css('border', '2px solid #3d3d3d');
        $('.post-creator-head, .post-creator-content').val('');

        //Shows the post
        $('.profile-post-creator-wrapper').after('<div class="userPost" id="post<?php echo $postId; ?>"><div class="form-wrapper popup-form-wrapper" id="post-settings-form-popup-wrapper"><div class="form-contents popup-form-contents" id="post-settings-form-popup-contents"><span class="closeX" id="post-settings-x" onclick="CloseModal(\'.form-contents\')">&times;</span><div class="post-settings-sec post-settings-edit" onclick="CloseModal(\'.form-contents\');OpenModal(\'#post-edit-form-popup-contents\');postEditPopUp();">Edit</div><div class="post-settings-sec post-settings-del" onclick="CloseModal(\'.form-contents\');OpenModal(\'#post-delete-form-popup-contents\')">Delete</div></div></div><div class="form-wrapper popup-form-wrapper" id="post-edit-form-popup-wrapper"><div class="form-contents popup-form-contents" id="post-edit-form-popup-contents"><span class="closeX" id="post-edit-x" onclick="CloseModal(\'.form-contents\')">&times;</span><div id="post-edit-form"><input type="text" id="post-edit-headline"><textarea type="text" id="post-edit-contents"></textarea><input type="text" value="<?php echo $postId; ?>" readonly style="display: none;"> <button type="submit" id="post-edit-submit" class="post-edit-submit" onclick="editPost();">Edit</button></div></div></div><div class="form-wrapper popup-form-wrapper" id="post-delete-form-popup-wrapper"><div class="form-contents popup-form-contents" id="post-delete-form-popup-contents"><span class="closeX" id="post-delete-x" onclick="CloseModal(\'.form-contents\')">&times;</span><p class="post-delete-txt">Are you sure you want to delete this post? There is no coming back</p><div class="post-delete-sec post-delete-yes" onclick="deletePost();">Yes</div><div class="post-delete-sec post-delete-no" onclick="CloseModal(\'.form-contents\');">No</div></div></div><a class="post-top"><div class="post-picture" style="background-image: url(<?php echo $_SESSION['croppedPic']; ?>);"></div><span class="post-un"><?php echo $_SESSION['username']; ?></span><div class="post-settings" onclick="OpenModal(\'#post-settings-form-popup-contents\')"></div></a> <div class="post-contents"><h2 class="post-headline"><?php echo htmlspecialchars(addslashes($postHead)); ?></h2><h3 class="post-text"><?php echo htmlspecialchars($postContent); ?></h3></div><div class="post-bottom"><span class="post-votes"><?php echo $likes; ?></span><div class="post-upvote"></div><div class="post-downvote"></div><span class="post-date"><?php echo date('d/m/Y', $timestamp); ?></span></div></div>');
        $('#post<?php echo $postId ?>').hide().delay(200).fadeIn(500);
    }
    
</script>