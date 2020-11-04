<?php

//Post http call to file
if (isset($_POST['action'])) {

    $chosenAction = $_POST['action'];

    //Gets the current user's id
    session_start();
    $user_id = $_SESSION['userId'];

    //Create and insert a new comment in the Db
    if ($chosenAction == "createComment") {
        if (isset($_POST['content']) && isset($_POST['postId'])) {
            post_comment($user_id, $_POST['postId'], $_POST['content']);
        }
        else {
            echo "fail";
        }
    }
    //Get all the comments for a given post
    else if ($chosenAction == "getComments") {
        if (isset($_POST['postId'])) {
            get_comments($_POST['postId']);
        }
    }
    //Up/downvotes a comment
    else if ($chosenAction == "upvote" || $chosenAction == "downvote") {
        if (isset($_POST['commentId'])) {
            vote_comment($_POST['commentId'], $chosenAction);
        }
    }
}

function get_single_comment ($uid, $uname, $upic, $cid, $clikes, $cdate, $content, $upvoted, $downvoted) {
    ?>
        <div class="single-comment comment-<?php echo $cid ?>" commentid="<?php echo $cid ?>">
            <a class="post-comment-top">
                <div class="post-comment-top-left">
                    <div class="post-picture post-comment-picture" style="background-image: url(<?php echo $upic; ?>);"></div>
                    <span class="post-un post-comment-un"><?php echo $uname; ?></span>
                </div>
                <div class="post-comment-top-right">
                    <span class="comment-votes"><?php echo $clikes; ?></span>
                    <div class="comment-upvote <?php if($upvoted) { echo 'voted'; } ?>"></div>
                    <div class="comment-downvote <?php if($downvoted) { echo 'voted'; } ?>"></div>
                    <span class="post-date post-comment-date"><?php echo $cdate; ?></span>
                </div>
            </a> 
            <div class="post-comment-contents">
                <h3 class="post-comment-text">
                    <?php echo htmlspecialchars($content); ?>
                </h3>
            </div>
        </div>
    <?php
}

//Inserts a new comment in the posts_comments table
function post_comment ($uid, $pid, $comment) {
    
    //Opens a connection to the database
    require '../includes/dbconnect.inc.php';
    $conn = OpenCon();
    
    $timestamp = time();
    $safeContent = htmlspecialchars($conn->real_escape_string($comment)); //Escapes the string and cnverts special text to the equivalent html code

    if ($res = $conn->query("INSERT INTO posts_comments (user_id, post_id, body, comment_timestamp) VALUES ($uid, $pid, '$safeContent', $timestamp)")) {
        if ($res2 = $conn->query("SELECT * FROM users WHERE user_id = $uid")) {
            if ($res2->num_rows) {
                while ($row = $res2->fetch_assoc()) {

                    //Get the id of the inserted comment
                    if ($res3 = $conn->query("SELECT comment_id from posts_comments WHERE user_id = $uid AND post_id = $pid AND comment_timestamp = $timestamp")) {
                        if ($res3->num_rows) {
                            while ($id_row = $res3->fetch_assoc()) {                                
                                echo get_single_comment($uid, $row['username'], $row['cropped_picture'], $id_row['comment_id'], 0, date('d/m/Y', $timestamp), $comment, false, false);
                            }
                        }
                    }

                }
            }
        }
    }

}

//Gets all comments for a given post
function get_comments ($pid) {

    //Opens a connection to the database
    require '../includes/dbconnect.inc.php';
    $conn = OpenCon();

    if ($res = $conn->query("SELECT * FROM posts_comments WHERE post_id = $pid ORDER BY comment_timestamp")) {
        if ($res->num_rows) {
            while ($row = $res->fetch_assoc()) {
                //Lookup users table
                if ($res2 = $conn->query("SELECT * FROM users WHERE user_id={$row['user_id']}")) {
                    if ($res2->num_rows) {
                        while ($user_row = $res2->fetch_assoc()) {
                            $uname = $user_row['username'];
                            $upic = $user_row['cropped_picture'];
                        }
                    }
                }
                
                $upvoted = $downvoted = false;
                $clikes = 0;
                //Lookup comments_likes table
                if ($res3 = $conn->query("SELECT * FROM comments_likes WHERE comment_id={$row['comment_id']}")) {
                    if ($res3->num_rows) {
                        while ($likes_row = $res3->fetch_assoc()) {


                            //This action has been taken by the current user
                            
                            //Post upvote
                            if ($likes_row['status'] == 1) {
                                $clikes++;

                                //Current user upvoted
                                if ($likes_row['user_id'] == $_SESSION['userId']) {
                                    $upvoted = true;
                                }
                            }
                            //Post downvote
                            else if ($likes_row['status'] == 0) {
                                $clikes--;

                                //Current user downvoted
                                if ($likes_row['user_id'] == $_SESSION['userId']) {
                                    $downvoted = true;
                                }
                            }
                        }
                    }
                }

                //$uid, $uname, $upic, $cid, $clikes, $cdate, $content, $upvoted, $downvoted
                get_single_comment ($row['user_id'], $uname, $upic, $row['comment_id'], $clikes, date('d/m/Y', $row['comment_timestamp']), $row['body'], $upvoted, $downvoted);
            }
        }
    }
}

function vote_comment ($cid, $action) {
    //Opens a connection to the database
    require '../includes/dbconnect.inc.php';
    $conn = OpenCon();

    //The id of the user trying to make the changes
    $userId = $_SESSION['userId'];

    if ($action == "upvote") $status = 1;
    if ($action == "downvote") $status = 0;

    if($result = $conn->query("SELECT * FROM comments_likes WHERE comment_id=$cid AND user_id=$userId") or die($conn->error)) {
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($status != $row['status']) { //If the user is switching their previous entry (from upvoted to downvoted and vice versa)
                    if ($res2 = $conn->query("UPDATE comments_likes SET status=$status WHERE comment_id=$cid AND user_id=$userId")) {
                        $returnObj = new \stdClass();
                        $returnObj->StatusCode = 10;
                        $returnObj->Content = "up/downvote changed in comment $cid";
                        $jsonRes = json_encode($returnObj);
                        echo $jsonRes;
                    }
                }
                else { //Remove the up/downvote
                    if ($res2 = $conn->query("DELETE FROM comments_likes WHERE comment_id=$cid AND user_id=$userId")) {
                        $returnObj = new \stdClass();
                        $returnObj->StatusCode = 10;
                        $returnObj->Content = "up/downvote removed";
                        $jsonRes = json_encode($returnObj);
                        echo $jsonRes;
                    }
                    else { //SQL Error
                        $returnObj = new \stdClass();
                        $returnObj->StatusCode = 29;
                        $returnObj->ErrorMsg = "SQL Error";
                        $jsonRes = json_encode($returnObj);
                        echo $jsonRes;
                    }
                }
            }
        }
        else { //No action taken before, add new entry to table
            if ($result = $conn->query("INSERT INTO comments_likes (comment_id, user_id, status) VALUES ($cid, $userId, $status)")) {
                $returnObj = new \stdClass();
                $returnObj->StatusCode = 10;
                $returnObj->Content = "up/downvote added to comment $cid";
                $jsonRes = json_encode($returnObj);
                echo $jsonRes;
            }
            else { //SQL Error
                $returnObj = new \stdClass();
                $returnObj->StatusCode = 29;
                $returnObj->ErrorMsg = "SQL Error";
                $jsonRes = json_encode($returnObj);
                echo $jsonRes;
            }
        }
    }
    else { //SQL Error
        $returnObj = new \stdClass();
        $returnObj->StatusCode = 29;
        $returnObj->ErrorMsg = "SQL Error";
        $jsonRes = json_encode($returnObj);
        echo $jsonRes;
    }
}