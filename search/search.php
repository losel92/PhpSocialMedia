<?php

if (isset($_POST['action'])) {
    
    $chosenAction = $_POST['action'];
    
    //Opens a connection to the database
    require '../includes/dbconnect.inc.php';
    $conn = OpenCon();

    //Searches through user posts
    if ($chosenAction == "searchPosts") {
        if (isset($_POST['text'])) {
            $searchTxt = $conn->real_escape_string($_POST['text']);

            if ($result = $conn->query("SELECT * FROM user_posts WHERE head LIKE '%$searchTxt%' OR content LIKE '%$searchTxt%'")) {
                if ($result->num_rows) {
                    while ($row = $result->fetch_assoc()) {
                        echo "{$row['head']} <br> {$row['content']}";
                    }
                }
            }
        }
    }
    //Searches for a user
    if ($chosenAction == "searchUsers") {
        if (isset($_POST['text'])) {
            $searchTxt = $conn->real_escape_string($_POST['text']);

            if ($result = $conn->query("SELECT * FROM users WHERE username LIKE '%$searchTxt%' OR first_name LIKE '%$searchTxt%' OR last_name LIKE '%$searchTxt%'")) {
                if ($result->num_rows) {
                    while ($row = $result->fetch_assoc()) {
                        echo "{$row['username']} <br> {$row['first_name']} <br> {$row['last_name']}";
                    }
                }
            }
        }
    }
}