<?php
    function getSinglePost($post_un, $post_date, $post_likes, $post_title, $post_content, $edit_date){
?>
    <div class="userPost">
        <a href="#" class="post-top">
            <div class="post-picture" style="background-image: url(<?php echo $_SESSION['croppedPic']; ?>);"></div>
            <span class="post-un"><?php echo $post_un; ?></span>
        </a> 
        <div class="post-contents">
            <h2 class="post-headline"><?php echo $post_title; ?></h2>
            <h3 class="post-text">
                <?php echo $post_content; ?>
            </h3>
        </div>
        <div class="post-bottom">
            <span class="post-votes"><?php echo $post_likes; ?></span>
            <div class="post-upvote"></div>
            <div class="post-downvote"></div>
            <span class="post-date"><?php echo $post_date; ?></span>
        </div> 
    </div>
<?php 
    }
?>
<!--
<div class="userPost">
        <a href="#" class="post-top">
            <div class="post-picture" style="background-image: url(<?php // echo $_SESSION['croppedPic']; ?>);"></div>
            <span class="post-un"><?php // echo $post_un; ?></span>
        </a> 
        <div class="post-contents">
            <h2 class="post-headline"><?php // echo $post_title; ?></h2>
            <h3 class="post-text">
            <?php // echo $post_content; ?>
            </h3>
        </div>
        <div class="post-bottom">
            <span class="post-votes"><?php // echo $post_likes; ?></span>
            <div class="post-upvote"></div>
            <div class="post-downvote"></div>
            <span class="post-date"><?php // echo $post_date; ?></span>
        </div> 
    </div>
-->