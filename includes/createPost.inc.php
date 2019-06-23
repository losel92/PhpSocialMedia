<?php
    if(isset($_POST['postContent'])){

        //Opens a connection to the database and starts the session
        require 'dbconnect.inc.php';
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
                    $postId = 'post'.$row[0];

                    $error = 'noError';
                    require '../userPosts.php';
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
        $('.profile-post-creator-wrapper').after('<div class="userPost" id="<?php echo $postId ?>"><a class="post-top"><div class="post-picture" style="background-image: url(<?php echo $_SESSION['croppedPic'] ?>);"></div><span class="post-un"><?php echo $_SESSION['username']; ?></span><div class="post-settings"></div></a> <div class="post-contents"><h2 class="post-headline"><?php echo htmlspecialchars(addslashes($postHead)); ?></h2><h3 class="post-text"><?php echo htmlspecialchars(addslashes($postContent)); ?></h3></div><div class="post-bottom"><span class="post-votes"><?php echo $likes; ?></span><div class="post-upvote"></div><div class="post-downvote"></div><span class="post-date"><?php echo date('d/m/Y', $timestamp); ?></span></div></div>');
        $('#<?php echo $postId ?>').hide().delay(200).fadeIn(500);
    }
    
</script>