<!-- This fuction takes all the post info and returns an HTML object -->

<?php
    function getSinglePost($post_id, $post_un, $post_date, $post_likes, $comment_count, $post_title, $post_content, $edit_date, $upvoted, $downvoted){
?>
    <div class="userPost" id="post<?php echo $post_id; ?>">

        <!-- The post settings popup form -->
        <div class="form-wrapper popup-form-wrapper" id="post-settings-form-popup-wrapper">
            <div class="form-contents popup-form-contents" id="post-settings-form-popup-contents">
                <span class="closeX" id="post-settings-x" onclick="CloseModal('.form-contents')">&times;</span>
                <div class="post-settings-sec post-settings-edit" onclick="CloseModal('.form-contents');OpenModal('#post-edit-form-popup-contents');postEditPopUp();">Edit</div>
                <div class="post-settings-sec post-settings-del" onclick="CloseModal('.form-contents');OpenModal('#post-delete-form-popup-contents')">Delete</div>
            </div>
        </div>

        <!-- The post edit popup form -->
        <div class="form-wrapper popup-form-wrapper" id="post-edit-form-popup-wrapper">
            <div class="form-contents popup-form-contents" id="post-edit-form-popup-contents">
                <span class="closeX" id="post-edit-x" onclick="CloseModal('.form-contents')">&times;</span>
                <div id="post-edit-form">
                    <input type="text" id="post-edit-headline">
                    <textarea type="text" id="post-edit-contents"></textarea>
                    <input type="text" value="<?php echo $post_id; ?>" readonly style="display: none;"> 
                    <button type="submit" id="post-edit-submit" class="post-edit-submit" onclick="editPost();">Save</button>
                </div>
            </div>
        </div>

        <!-- The post delete popup form -->
        <div class="form-wrapper popup-form-wrapper" id="post-delete-form-popup-wrapper">
            <div class="form-contents popup-form-contents" id="post-delete-form-popup-contents">
                <span class="closeX" id="post-delete-x" onclick="CloseModal('.form-contents')">&times;</span>
                <p class="post-delete-txt">Are you sure you want to delete this post? There is no coming back</p>
                <div class="post-delete-sec post-delete-yes" onclick="deletePost();">Yes</div>
                <div class="post-delete-sec post-delete-no" onclick="CloseModal('.form-contents');">No</div>
            </div>
        </div>

        <a class="post-top">
            <div class="post-picture" style="background-image: url(<?php echo $_SESSION['croppedPic']; ?>);"></div>
            <span class="post-un"><?php echo $post_un; ?></span>
            <span class="post-comments-count"><?php echo $comment_count; ?></span>
            <div class="post-comments" onclick="OpenModal('#post-comments-form-popup-contents')"></div>
            <div class="post-settings" onclick="OpenModal('#post-settings-form-popup-contents')"></div>
        </a> 
        <div class="post-contents">
            <h2 class="post-headline"><?php echo htmlspecialchars($post_title); ?></h2>
            <h3 class="post-text">
                <?php echo htmlspecialchars($post_content); ?>
            </h3>
        </div>
        <div class="post-bottom">
            <span class="post-votes"><?php echo $post_likes; ?></span>
            <div class="post-upvote <?php if($upvoted) { echo 'voted'; } ?>"></div>
            <div class="post-downvote <?php if($downvoted) { echo 'voted'; } ?>"></div>
            <span class="post-date"><?php echo $post_date; ?></span>
        </div>
    </div>
<?php 
    }

    function getAllPostsForSingleUser($uid) {
        //Opens a connection to the database
        require 'includes/dbconnect.inc.php';
        $conn = OpenCon();

        $sql = "SELECT * FROM user_posts WHERE user_id=$uid ORDER BY post_timestamp DESC";
        if($result = mysqli_query($conn, $sql)){
            //If the user has posted anything // ie. if the query returns any values
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $postId = $row['post_id'];
                    $likes = 0;
                    $comments = 0;
                    $userUp = false;
                    $userDown = false;

                    //Get and calculate likes (upvotes and downvotes)
                    $likesSql = "SELECT * FROM posts_likes WHERE post_id=$postId";
                    if ($likesResult = mysqli_query($conn, $likesSql)) {
                        if (mysqli_num_rows($likesResult)) {
                            while ($likesRow = mysqli_fetch_assoc($likesResult)) {
                                if ($likesRow['status'] == 1) { $likes++; }
                                else if ($likesRow['status'] == 0) { $likes--; }

                                //Finds out if the user has up/downvoted the current post
                                if ($likesRow['user_id'] == $_SESSION['userId']) {
                                    if ($likesRow['status'] == 0) {
                                        $userUp = false;
                                        $userDown = true;
                                    }
                                    else if ($likesRow['status'] == 1) {
                                        $userUp = true;
                                        $userDown = false;
                                    }
                                }

                            }
                        }
                    }

                    //Calculates comments
                    if ($commentsRes = $conn->query("SELECT * FROM posts_comments WHERE post_id=$postId")) {
                        if ($commentsRes->num_rows) {
                            while ($comment_count->fetch_assoc()) {
                                $comments++;
                            }
                        }
                    }

                    //Get each post individually
                    getSinglePost($row['post_id'] ,$row['username'], date('d/m/Y',$row['post_timestamp']), $likes, $comments, $row['head'], $row['content'], $row['edit_timestamp'], $userUp, $userDown);
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

        //Closes the connection
        $conn->close();
    }