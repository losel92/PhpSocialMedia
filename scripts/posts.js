/// Functions and handlers for creating, deleting and updating existing posts

var lastClickedPost
//Post editing popup form
function postEditPopUp() {
  console.log("edit " + lastClickedPost)
  //Sets the text in the edit textbox to match the post text
  var editHead = $("#" + lastClickedPost)
    .children(".post-contents")
    .children(".post-headline")
    .text()
  var editContents = $("#" + lastClickedPost)
    .children(".post-contents")
    .children(".post-text")
    .text()
  editContents = editContents.trimStart().trimEnd()
  $("#post-edit-headline").val(editHead)
  $("#post-edit-contents").val(editContents)
}

//Post Editing submit
function editPost() {
  //Gets the edited headline and post content
  var postHead = $("#post-edit-headline").val()
  var postContents = $("#post-edit-contents").val()
  var postId = lastClickedPost

  console.log("hey we're here boi")

  //Sends an ajax request to post-modify.inc.php
  $.ajax({
    type: "POST",
    url: "./posts/post-modify.inc.php",
    data: { postHead: postHead, postContents: postContents, postId: postId },
    success: function(status) {
      if (status == 0) {
        alert(
          "There was an error when trying to edit your post, please try again later."
        )
      } else if (status == 1) {
        console.log("Post Succesfully Edited")
        location.reload()
      } else {
        console.log(status)
      }
    }
  })
}

//Post Delete
function deletePost() {
  var theId = lastClickedPost

  //Sends an ajax request to post-modify.inc.php
  $.ajax({
    type: "POST",
    url: "./posts/post-modify.inc.php",
    data: { theId: theId },
    success: function(status) {
      if (status == 0) {
        alert(
          "There was an error when trying to delete your post, please try again later."
        )
      } else if (status == 1) {
        console.log("Post Succesfully Deleted")
        location.reload()
      } else {
        console.log(status)
      }
    }
  })
}

$(document).ready(() => {
  //Runs when the user tries to upload a new post
  $(".post-creator-btn").click(function(event) {
    //Prevents the submit button from redirecting to createPost.inc.php
    event.preventDefault()

    //Gets the title and content of the new post
    var postHead = $(".post-creator-head").val()
    var postContent = $(".post-creator-content").val()

    //Passes the information to the php file
    $("#post-creator-load").load("posts/createPost.inc.php", {
      postHead: postHead,
      postContent: postContent
    })
  })

  var clickedPostID
  //Gets the selected post to edit / delete
  $(document).on("click", ".post-settings", function() {
    clickedPostID = $(this)
      .parent()
      .parent()
      .attr("id")
    console.log(clickedPostID)
    lastClickedPost = clickedPostID
  })
})
