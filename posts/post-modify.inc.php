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

    //Checks if the postId was passed, if not returns an error
    if (isset($_POST['postId'])) {
        $postId = $_POST['postId'];
    }
    else {
        echo false;
    }

    //Upvote / Downvote post
    if ($action == "upvote" || $action == "downvote" ) {
        $status = 1;

        //Opens a connection to the database
        require '../includes/dbconnect.inc.php';
        $conn = OpenCon();
        session_start();

        //The id of the user trying to make the changes
        $userId = $_SESSION['userId'];

        //Timestamp of the action
        $edit_time = time();
        
        $sql = "SELECT * FROM user_posts WHERE post_id=$postId AND user_id=$userId";

        if($result = mysqli_query($conn, $sql)){
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    
                    $likes = $row['likes']; //Gets the current amount of likes
                    $likes = $action == "upvote" ? $likes+1 : $likes-1; //Increases or decreases that number by one depending on the action chosen

                    //Actually updates the post in the database
                    $new_sql = "UPDATE user_posts SET likes=$likes WHERE post_id=$postId";
                    
                    if ($new_result = mysqli_query($conn, $new_sql)) { //Success
                        $returnObj = new \stdClass();
                        $returnObj->StatusCode = 10;
                        $returnObj->Content = $likes;
                        $jsonRes = json_encode($returnObj);
                        echo $jsonRes;
                    }
                    else { //SQL Error
                        $returnObj = new \stdClass();
                        $returnObj->StatusCode = 10;
                        $returnObj->ErrorMsg = "SQL Error";
                        $jsonRes = json_encode($returnObj);
                        echo $jsonRes;
                    }
                }
            }
            else{ //SQL Error
                $returnObj = new \stdClass();
                $returnObj->StatusCode = 10;
                $returnObj->ErrorMsg = "postId or userId mismatch";
                $jsonRes = json_encode($returnObj);
                echo $jsonRes;
            }
        }
        //There was an error
        else{
            $returnObj = new \stdClass();
            $returnObj->StatusCode = 10;
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