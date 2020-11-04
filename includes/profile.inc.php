<?php

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == "followUser") {
        if (isset($_POST['userId'])) {
            followUser($_POST['userId']);
        }
        else {
            echo "userId can't be empty";
        }
    }
    else if ($action == "unfollowUser") {
        if (isset($_POST['userId'])) {
            unfollowUser($_POST['userId']);
        }
        else {
            echo "userId can't be empty";
        }
    }

}

function followUser ($userId) {
    //Connection to db
    include_once("dbconnect.inc.php");
    $conn = OpenCon();

    //Starts session if one is not already in place
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if ($res = $conn->query("SELECT Null FROM followers WHERE follower_id={$_SESSION['userId']} AND following_id=$userId")) {
        if ($res->num_rows) {
            echo "User already being followed";
        }
        else {
            if ($res = $conn->query("INSERT INTO followers VALUES ({$_SESSION['userId']}, $userId)")) {
                echo "Success";
            }
        }
    }
}

function unfollowUser ($userId) {
    //Connection to db
    include_once("dbconnect.inc.php");
    $conn = OpenCon();

    //Starts session if one is not already in place
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if ($res = $conn->query("SELECT Null FROM followers WHERE follower_id={$_SESSION['userId']} AND following_id=$userId")) {
        //The user is being followed
        if ($res->num_rows) {
            //Remove the entry
            if ($res2 = $conn->query("DELETE FROM followers WHERE follower_id={$_SESSION['userId']} AND following_id=$userId")) {
                echo "Success";
            }
        }
        else {
            echo "User not being followed";
        }
    }
}