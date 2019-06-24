<?php

if(isset($_POST['fname'])){

    $status = 1;

    //Opens a connection to the database
	require 'dbconnect.inc.php';
    $conn = OpenCon();
    session_start();

    //gets the variables from the post request + the userID
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $bday = $_POST['bday'];
    $userId = $_SESSION['userId'];

    $sql = "UPDATE users SET email=?, first_name=?, last_name=?, birthday=? WHERE user_id=?";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $status = 0;
    }
    else{
        //Updates the info in the database
        mysqli_stmt_bind_param($stmt, "ssssi", $email, $fname, $lname, $bday, $userId);
        mysqli_stmt_execute($stmt);

        //Updates the session info
        $_SESSION['email'] = $email;
        $_SESSION['firstName'] = $fname;
        $_SESSION['lastName'] = $lname;
        $_SESSION['birthday'] = $bday;
    }
    echo $status;
}
//If the user simply types in the url of this file, they will be redirected to the start page
else{
    header('Location: ../index.php');
}