<?php
function get_comment_section ($post_id, $user_id, $post_un, $post_likes, $upvoted, $downvoted) {
    
    //Opens a connection to the database
    $conn = OpenCon();

    $post_title;
    $post_content;
    $post_date;
    
    //Gets the post object
    if ($res = $conn->query("SELECT * FROM user_posts WHERE post_id=$post_id")) {
        if ($res->num_rows) {
            while ($row = $res->fetch_assoc()) {
                $post_title = $row['head'];
                $post_content = $row['content'];
                $post_date = date('d/m/Y',$row['post_timestamp']);
            }
        }
        else {
            echo "<script>alert(SQL Error)</script>";
            return;
        }
    }
    else {
        echo "<script>alert(SQL Error)</script>";
        return;
    }
?>
<div class="postComment postid-<?php echo $post_id; ?>" postId="<?php echo $post_id; ?>">
    <a class="post-comment-top">
        <div class="post-comment-top-left">
            <div class="post-picture post-comment-picture" style="background-image: url(<?php echo $_SESSION['croppedPic']; ?>);"></div>
            <span class="post-un post-comment-un"><?php echo $post_un; ?></span>
        </div>
        <div class="post-comment-top-right">
            <span class="post-votes"><?php echo $post_likes; ?></span>
            <div class="post-upvote <?php if($upvoted) { echo 'voted'; } ?>"></div>
            <div class="post-downvote <?php if($downvoted) { echo 'voted'; } ?>"></div>
            <span class="post-date post-comment-date"><?php echo $post_date; ?></span>
        </div>
    </a> 
    <div class="post-comment-contents">
        <h2 class="post-comment-headline"><?php echo htmlspecialchars($post_title); ?></h2>
        <h3 class="post-comment-text">
            <?php echo htmlspecialchars($post_content); ?>
        </h3>
    </div>
</div>
<?php
}

function get_single_comment ($comment_id) {
    ?>
        <div>
            
        </div>
    <?php
}