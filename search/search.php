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

                        require '../posts/userPosts.php';
                        getSinglePost($row['post_id'], $row['username'], date('d/m/Y',$row['post_timestamp']), $post_likes, $post_comments, $row['head'], $row['content'], $row['edit_timestamp'], $userUp, $userDown);
                        //echo "{$row['head']} <br> {$row['content']}";
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
                        echo "{$row['username']} <br> {$row['first_name']} <br> {$row['last_name']}";
                    }
                }
            }
        }
    }
}