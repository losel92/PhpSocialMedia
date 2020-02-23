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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `user_posts`
--

CREATE TABLE `user_posts` (
  `post_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `post_timestamp` int(11) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `head` varchar(64) DEFAULT NULL,
  `content` varchar(12000) DEFAULT NULL,
  `edit_timestamp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `posts_likes`
--

CREATE TABLE posts_likes (
	id int(64) PRIMARY KEY AUTO_INCREMENT,
    user_id int(11),
    post_id int(11),
    status int(5)
)

--
-- Table structure for table `posts_comments`
--

CREATE TABLE posts_comments (
	id int(64) PRIMARY KEY AUTO_INCREMENT,
    user_id int(11),
    post_id int(11),
    body varchar(1000)
)