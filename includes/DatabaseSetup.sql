--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(16) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `first_name` varchar(64) DEFAULT NULL,
  `last_name` varchar(64) DEFAULT NULL,
  `pwd` varchar(256) DEFAULT NULL,
  `gender` varchar(32) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `phone_number` varchar(32) DEFAULT NULL,
  `profile_picture` varchar(256) DEFAULT NULL,
  `cropped_picture` varchar(1024) DEFAULT NULL
)

--
-- Table structure for table `user_posts`
--

CREATE TABLE `user_posts` (
  `post_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `post_timestamp` int(11) DEFAULT NULL,
  `head` varchar(64) DEFAULT NULL,
  `content` varchar(12000) DEFAULT NULL,
  `edit_timestamp` int(11) DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)

--
-- Table structure for table `posts_likes`
--

CREATE TABLE posts_likes (
    like_id int(11) NOT NULL AUTO_INCREMENT,
    user_id int(11),
    post_id int(11),
    status int(5),
    PRIMARY KEY (like_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES user_posts(post_id) ON DELETE CASCADE
)

--
-- Table structure for table `posts_comments`
--

CREATE TABLE posts_comments (
    comment_id int(11) NOT NULL AUTO_INCREMENT,
    user_id int(11),
    post_id int(11),
    body varchar(1000),
    comment_timestamp int(11) DEFAULT NULL,
    PRIMARY KEY (comment_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES user_posts(post_id) ON DELETE CASCADE
)

--
-- Table structure for table `posts_comments`
--

CREATE TABLE comments_likes (
    like_id int(11) NOT NULL AUTO_INCREMENT,
    comment_id int(11) NOT NULL,
    user_id int(11) NOT NULL,
    status int(1),
    PRIMARY KEY (like_id),
    FOREIGN KEY (comment_id) REFERENCES posts_comments(comment_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)

--
-- Table structure for table `followers`
--

CREATE TABLE followers (
  follower_id int(11) NOT NULL,
  following_id int(11) NOT NULL,
  PRIMARY KEY (follower_id, following_id),
  FOREIGN KEY (follower_id) REFERENCES users(user_id),
  FOREIGN KEY (following_id) REFERENCES users(user_id)
)