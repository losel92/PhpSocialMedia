<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['userId'])) {

    require 'header.php';
    
    ?>

    <link rel="stylesheet" type="text/css" href="content/search.css">
    <script type="text/javascript" src="scripts/posts.js"></script>

    <main style="min-height: 91.4vh;">
        <script type="text/javascript" src="scripts/search.js"></script>
        
        <form action="" id="searchForm">
            <input type="text" id="searchTxt">
            <div id="searchSubmit" class="btn">submit</div>
        </form>
        
        <div id="searchResults">
            <div id="result-btns-wrapper">
                <button id="search-posts-btn" class="btn btn-selected">Posts</button>
                <button id="search-users-btn" class="btn">Users</button>
            </div>
            <div class="search-result-posts"></div>
            <div class="search-result-users" style="display: none"></div>
        </div>
    </main>
    
    <?php
    
    require 'footer.php';

} else {
    header("Location: index.php");
}