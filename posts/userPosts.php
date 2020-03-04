<!-- This fuction takes all the post info and returns an HTML object -->

<?php
    function getSinglePost($post_id, $post_un, $post_pic, $post_date, $post_likes, $comment_count, $post_title, $post_content, $edit_date, $upvoted, $downvoted){
?>
    <div class="userPost postid-<?php echo $post_id; ?>" id="post<?php echo $post_id; ?>" postId="<?php echo $post_id; ?>">

        <!-- Post comments popup section -->
        <div class="form-wrapper popup-form-wrapper">
            <div class="form-contents popup-form-contents post-comments-form-popup-contents" id="post<?php echo $post_id; ?>-comment-form">
                <span class="closeX" id="post-comments-x" onclick="CloseModal('.form-contents')">&times;</span>
                <div class="comments-modal-content">
                    <?php get_comment_section($post_id, $_SESSION['userId'], $post_un, $post_pic, $post_likes, $upvoted, $downvoted); ?>
                </div>
            </div>
        </div>

        <!-- Post settings popup form -->
        <div class="form-wrapper popup-form-wrapper">
            <div class="form-contents popup-form-contents post-settings-form-popup-contents" id="post<?php echo $post_id; ?>-settings-form-popup-contents">
                <span class="closeX" id="post-settings-x" onclick="CloseModal('.form-contents')">&times;</span>
                <div class="post-settings-sec post-settings-edit" onclick="CloseModal('.form-contents');OpenModal('#post<?php echo $post_id; ?>-edit-form-popup-contents');postEditPopUp();">Edit</div>
                <div class="post-settings-sec post-settings-del" onclick="CloseModal('.form-contents');OpenModal('#post<?php echo $post_id; ?>-delete-form-popup-contents')">Delete</div>
            </div>
        </div>

        <!-- Edit post popup form -->
        <div class="form-wrapper popup-form-wrapper">
            <div class="form-contents popup-form-contents post-edit-form-popup-contents" id="post<?php echo $post_id; ?>-edit-form-popup-contents">
                <span class="closeX" id="post-edit-x" onclick="CloseModal('.form-contents')">&times;</span>
                <div id="post-edit-form">
                    <input type="text" id="post-edit-headline">
                    <textarea type="text" id="post-edit-contents"></textarea>
                    <input type="text" value="<?php echo $post_id; ?>" readonly style="display: none;"> 
                    <button type="submit" id="post-edit-submit" class="post-edit-submit" onclick="editPost();">Save</button>
                </div>
            </div>
        </div>

        <!-- Delete post popup form -->
        <div class="form-wrapper popup-form-wrapper">
            <div class="form-contents popup-form-contents post-delete-form-popup-contents" id="post<?php echo $post_id; ?>-delete-form-popup-contents">
                <span class="closeX" id="post-delete-x" onclick="CloseModal('.form-contents')">&times;</span>
                <p class="post-delete-txt">Are you sure you want to delete this post? There is no coming back</p>
                <div class="post-delete-sec post-delete-yes" onclick="deletePost();">Yes</div>
                <div class="post-delete-sec post-delete-no" onclick="CloseModal('.form-contents');">No</div>
            </div>
        </div>

        <!-- Actual post -->
        <a class="post-top">
            <div style="display: flex;">
                <div class="post-picture" style="background-image: url(<?php echo $post_pic; ?>);"></div>
                <span class="post-un"><?php echo $post_un; ?></span>
            </div>
            <div style="display: flex; align-items: center;">
                <span class="post-comments-count"><?php echo $comment_count; ?></span>
                <div class="post-comments" onclick="OpenModal('#post<?php echo $post_id; ?>-comment-form')"></div>
                <div class="post-settings" onclick="OpenModal('#post<?php echo $post_id; ?>-settings-form-popup-contents')"></div>
            </div>
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
        require_once 'includes/dbconnect.inc.php';
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
                            while ($commentsRes->fetch_assoc()) {
                                $comments++;
                            }
                        }
                    }

                    //Gets username and profile pic
                    if ($usersRes = $conn->query("SELECT username, cropped_picture FROM users WHERE user_id = {$row['user_id']}")) {
                        if ($usersRes->num_rows) {
                            while ($userRow = $usersRes->fetch_assoc()) {
                                //Get each post individually
                                getSinglePost($row['post_id'] ,$userRow['username'], $userRow['cropped_picture'], date('d/m/Y',$row['post_timestamp']), $likes, $comments, $row['head'], $row['content'], $row['edit_timestamp'], $userUp, $userDown);
                            }
                        }
                    }
                    
                }
            }
            //If the user hasn't posted anything yet
            else{
                if ($_SESSION['userId'] == $uid) {
                    echo "Your posts will appear here!";
                }
                else {
                    echo "This user doesn't have any posts yet";
                }
            }
        }
        //There was an error
        else{
            ?><script>console.log("SQL Error");</script><?php
        }

        //Closes the connection
        $conn->close();
    }

    function get_comment_section ($post_id, $user_id, $post_un, $post_pic, $post_likes, $upvoted, $downvoted) {
        //Opens a connection to the database
        $conn = OpenCon();
    
        $post_title;
        $post_content;
        $post_date;
        
        //Gets the post object
        if ($res = $conn->query("SELECT * FROM user_posts WHERE post_id=$post_id")) {
            if ($res->num_rows) {
                while ($row = $res->fetch_assoc()) {
                    $post_title = $row['head'];
                    $post_content = $row['content'];
                    $post_date = date('d/m/Y',$row['post_timestamp']);
                }
            }
            else {
                echo "<script>alert(SQL Error)</script>";
                return;
            }
        }
        else {
            echo "<script>alert(SQL Error)</script>";
            return;
        }
    ?>
        <div class="postComment postid-<?php echo $post_id; ?>" postId="<?php echo $post_id; ?>">
            <a class="post-comment-top">
                <div class="post-comment-top-left">
                    <div class="post-picture post-comment-picture" style="background-image: url(<?php echo $post_pic; ?>);"></div>
                    <span class="post-un post-comment-un"><?php echo $post_un; ?></span>
                </div>
                <div class="post-comment-top-right">
                    <span class="post-votes"><?php echo $post_likes; ?></span>
                    <div class="post-upvote <?php if($upvoted) { echo 'voted'; } ?>"></div>
                    <div class="post-downvote <?php if($downvoted) { echo 'voted'; } ?>"></div>
                    <span class="post-date post-comment-date"><?php echo $post_date; ?></span>
                </div>
            </a> 
            <div class="post-comment-contents">
                <h2 class="post-comment-headline"><?php echo htmlspecialchars($post_title); ?></h2>
                <h3 class="post-comment-text">
                    <?php echo htmlspecialchars($post_content); ?>
                </h3>
            </div>
        </div>
        <div class="make-comment-section">
            <div class="make-comment-textarea-wrapper">
                <textarea class="make-comment-textarea" placeholder="Write a new comment..."></textarea>
            </div>
            <div style="text-align: right">
                <button class="submit-comment-btn" style="display: none">Submit!</button>
            </div>
        </div>
        <div class="post-comments-comments" style="display: none;"><!-- Comments will be injected here --></div>
    <?php
    }