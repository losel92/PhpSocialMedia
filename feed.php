<?php

require_once "header.php";

?> <script type="text/javascript" src="scripts/posts.js"></script> <?php
?> <script type="text/javascript" src="scripts/comments.js"></script> <?php
?> <link rel="stylesheet" type="text/css" href="content/search.css"> <?php

?> 
<main style="min-height: 89vh;padding-top: 25px;"> 
<?php

    //Opens a connection to the database
    require_once 'includes/dbconnect.inc.php';
    $conn = OpenCon();

    //Starts a session if one doesn't already exist
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $following = array();

    //Gets a list of the users the current user follows
    if ($res = $conn->query("SELECT * FROM followers WHERE follower_id={$_SESSION['userId']}")) {
        if ($res->num_rows) {
            while ($row = $res->fetch_assoc()) {
                array_push($following, $row['following_id']);
            }
        }
    }

    //Gets all the users in the query
    $query = "SELECT * from user_posts WHERE user_id=-1";
    foreach ($following as $id) {
        $query .= " OR user_id=$id";
    }

    if ($res = $conn->query($query)) {
        if ($res->num_rows) {

            require 'posts/userPosts.php';

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
                            getSinglePost($row['user_id'], $row['post_id'], $row4['username'], $row4['cropped_picture'], date('d/m/Y',$row['post_timestamp']), $post_likes, $post_comments, $row['head'], $row['content'], $row['edit_timestamp'], $userUp, $userDown);
                        }
                    }
                }

            }
        }
    }

?> </main> <?php

require_once "footer.php";