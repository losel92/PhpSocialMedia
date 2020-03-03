<?php

if (isset($_POST['action'])) {
    
    $chosenAction = $_POST['action'];
    
    //Opens a connection to the database
    require '../includes/dbconnect.inc.php';
    $conn = OpenCon();

    //Starts a session if one doesn't already exist
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    //Searches through user posts
    if ($chosenAction == "searchPosts") {
        if (isset($_POST['text'])) {
            $searchTxt = $conn->real_escape_string($_POST['text']);

            if ($res = $conn->query("SELECT * FROM user_posts WHERE head LIKE '%$searchTxt%' OR content LIKE '%$searchTxt%'")) {
                if ($res->num_rows) {

                    require '../posts/userPosts.php';

                    while ($row = $res->fetch_assoc()) {
                    
                        $post_likes = 0;
                        $post_comments = 0;

                        $userUp = false;
                        $userDown = false;

                        //Calculates up/downvotes for the post
                        if ($res2 = $conn->query("SELECT * FROM posts_likes WHERE post_id={$row['post_id']}")) {
                            if ($res2->num_rows) {
                                while ($row2 = $res2->fetch_assoc()) {
                                    if ($row2['status'] == 1) { //Up
                                        $post_likes++;
    
                                        //Means the current user has upvoted the post
                                        if ($row2['user_id'] == $_SESSION['userId']) {
                                            $userUp = true;
                                        }
                                    } else if ($row2['status'] == 0) { //Down
                                        $post_likes--;
                                        
                                        //Means the current user has downvoted the post
                                        if ($row2['user_id'] == $_SESSION['userId']) {
                                            $userDown = true;
                                        }
                                    }
                                }
                            }
                        }

                        //Gets the comment count for the post
                        if ($res3 =  $conn->query("SELECT null FROM posts_comments WHERE post_id = {$row['post_id']}")) {
                            if ($res3->num_rows) {
                                while ($row3 = $res3->fetch_assoc()) {
                                    $post_comments++;
                                }
                            }
                        }

                        //Gets the username and picture
                        if ($res4 =  $conn->query("SELECT username, cropped_picture FROM users WHERE user_id = {$row['user_id']}")) {
                            if ($res4->num_rows) {
                                while ($row4 = $res4->fetch_assoc()) {
                                    getSinglePost($row['post_id'], $row4['username'], $row4['cropped_picture'], date('d/m/Y',$row['post_timestamp']), $post_likes, $post_comments, $row['head'], $row['content'], $row['edit_timestamp'], $userUp, $userDown);
                                }
                            }
                        }

                    }
                }
            }
        }
    }
    //Searches for a user
    if ($chosenAction == "searchUsers") {
        if (isset($_POST['text'])) {
            $searchTxt = $conn->real_escape_string($_POST['text']);

            if ($res = $conn->query("SELECT * FROM users WHERE username LIKE '%$searchTxt%' OR first_name LIKE '%$searchTxt%' OR last_name LIKE '%$searchTxt%'")) {
                if ($res->num_rows) {
                    while ($row = $res->fetch_assoc()) {

                        if($row['gender'] == "male") {
                            $gender = 'M';
                        } else if($row['gender'] == "female") {
                            $gender = 'F';
                        }
                        else {
                            $gender = 'U';
                        }

                        ?>

                        <div class="single-user-profile-short">
                            <a href="#"><div class="user-profile-short-pic" style="background-image: url(<?php echo $row['cropped_picture']; ?>)"></div></a>
                            <div class="user-profile-short-info">
                                <div class="user-profile-info-top">
                                    <a href="#"><span class="user-profile-info-un custom-btn"><?php echo $row['username']; ?></span></a>
                                    <span class="user-profile-info-age"><?php echo $row['age']; ?></span>
                                    <span class="user-profile-info-gender"><?php echo $gender; ?></span>
                                    <button class="user-profile-follow-btn custom-btn"></button>
                                </div>
                                <div class="user-profile-info-bottom">
                                    <span class="user-profile-info-fn"><?php echo $row['first_name']; ?></span>
                                    <span class="user-profile-info-ln"><?php echo $row['last_name']; ?></span>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                }
            }
        }
    }
}