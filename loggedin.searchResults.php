<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['userId'])) {

    require 'header.php';
    
    ?>
    <main style="min-height: 91.4vh;">
        <script type="text/javascript" src="scripts/search.js"></script>
        
        <form action="" id="searchForm">
            <input type="text" id="searchTxt">
            <button id="searchSubmit">submit</button>
        </form>
        
        <div id="searchResults">
            <div class="search-result-users"></div>
            <div class="search-result-posts"></div>
        </div>
    </main>
    
    <?php
    
    require 'footer.php';

} else {
    header("Location: index.php");
}