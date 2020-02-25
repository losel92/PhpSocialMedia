<?php

//Post Edit
if (isset($_POST['postHead'])) {
    
    $status = 1;

    //Opens a connection to the database
	require '../includes/dbconnect.inc.php';
    $conn = OpenCon();
    session_start();

    $postHead = $_POST['postHead'];
    $postContents = $_POST['postContents'];
    $postId = $_POST['postId'];
    $userId = $_SESSION['userId'];
    $edit_time = time();
    
    $sql = "SELECT * FROM user_posts WHERE post_id=? AND user_id=?";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $status = 0;
    }
    else{
        mysqli_stmt_bind_param($stmt, "ii", $postId, $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        //If there are no results, it means the id doesn't match
        //or it isn't the post author who is trying to make a change
        $rowCount = mysqli_stmt_num_rows($stmt);
        if ($rowCount < 1) {
            $status = 0;
        }
        else{
            //Actually edits the post in the database
            $sql = "UPDATE user_posts SET head=?, content=?, edit_timestamp=? WHERE post_id=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                $status = 0;
            }
            else{
                mysqli_stmt_bind_param($stmt, "ssii", $postHead, $postContents, $edit_time, $postId);
                mysqli_stmt_execute($stmt);
            }
        }
    }
    echo $status;
}
//Post Delete
else if (isset($_POST['theId'])) {

    $status = 1;

    //Opens a connection to the database
	require '../includes/dbconnect.inc.php';
    $conn = OpenCon();
    session_start();

    $postId = $_POST['theId'];
    $userId = $_SESSION['userId'];

    $sql = "SELECT * FROM user_posts WHERE post_id=? AND user_id=?";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $status = 0;
    }
    else{
        mysqli_stmt_bind_param($stmt, "ii", $postId, $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        //If there are no results, it means the id doesn't match
        //or it isn't the post author who is trying to delete it
        $rowCount = mysqli_stmt_num_rows($stmt);
        if ($rowCount < 1) {
            $status = 0;
        }
        else{
            //Actually deletes the post from the database
            $sql = "DELETE FROM user_posts WHERE post_id=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                $status = 0;
            }
            else{
                mysqli_stmt_bind_param($stmt, "i", $postId);
                mysqli_stmt_execute($stmt);
            }
        }
    }
    echo $status;
}
else if (isset($_POST['action'])) {

    $postId;
    $action = $_POST['action'];

    //Timestamp of the action
    $edit_time = time();

    //Checks if the postId was passed, if not returns an error
    if (isset($_POST['postId'])) {
        $postId = $_POST['postId'];
    }
    else {
        echo false;
    }

    //Upvote / Downvote post
    if ($action == "upvote" || $action == "downvote" ) {
        $status = $action == "upvote" ? 1 : 0;

        //Opens a connection to the database
        require '../includes/dbconnect.inc.php';
        $conn = OpenCon();
        session_start();

        //The id of the user trying to make the changes
        $userId = $_SESSION['userId'];

        $result = $conn->query("SELECT * FROM posts_likes WHERE `post_id`=2 AND `user_id`=1") or die($conn->error);
        if(true) {
             if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($status != $row['status']) { //If the user is switching their previous entry (from upvoted to downvoted and vice versa)
                        if ($res2 = $conn->query("UPDATE posts_likes SET status=$status WHERE post_id=$postId AND user_id=$userId")) {
                            $returnObj = new \stdClass();
                            $returnObj->StatusCode = 10;
                            $returnObj->Content = "up/downvote changed in post $postId";
                            $jsonRes = json_encode($returnObj);
                            echo $jsonRes;
                        }
                    }
                    else { //Trying to up/downvote a post twice, no action taken
                        $returnObj = new \stdClass();
                        $returnObj->StatusCode = 11;
                        $returnObj->ErrorMsg = "can't up/downvote a post twice";
                        $jsonRes = json_encode($returnObj);
                        echo $jsonRes;
                    }
                }
            }
            else { //No action taken before, add new entry to table
                if ($result = $conn->query("INSERT INTO posts_likes VALUES ($userId, $postId, $status)")) {
                    $returnObj = new \stdClass();
                    $returnObj->StatusCode = 10;
                    $returnObj->Content = "up/downvote added to post $postId";
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
    else {
        header("Location: ../index.php");
    }
}
//If the user simply enters the url to this page they'll be redirected to index.php
else {
    header("Location: ../index.php");
}