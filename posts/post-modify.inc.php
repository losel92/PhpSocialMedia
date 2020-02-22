<?php

//Post Edit
if(isset($_POST['postHead'])){
    
    $status = 1;

    //Opens a connection to the database
	require 'dbconnect.inc.php';
    $conn = OpenCon();
    session_start();

    $postHead = $_POST['postHead'];
    $postContents = $_POST['postContents'];
    $postId = substr($_POST['postId'], 4);
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
else if(isset($_POST['theId'])){

    $status = 1;

    //Opens a connection to the database
	require 'dbconnect.inc.php';
    $conn = OpenCon();
    session_start();

    $postId = substr($_POST['theId'], 4);
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
//If the user simply enters the url to this page they'll be redirected to index.php
else{
    header("Location: ../index.php");
}