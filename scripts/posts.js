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
  //Strores the id of the last clicked post
  $(document).on("click", ".userPost", function(e) {
    clickedPostID = $(this).attr("postId")
    console.log(clickedPostID)
    lastClickedPost = "post" + clickedPostID

    let chosenAction = ""
    let likes = parseInt(
      $(e.target)
        .siblings(".post-votes")
        .html()
    )

    //Upvote the post
    if ($(e.target).hasClass("post-upvote")) {
      console.log("Upvote me!")
      chosenAction = "upvote"
    }
    //Downvote the post
    else if ($(e.target).hasClass("post-downvote")) {
      console.log("Downvote me!")
      chosenAction = "downvote"
    }

    console.log({
      action: chosenAction,
      postId: clickedPostID
    })
    //We actually want to update something
    if (chosenAction != "") {
      //Calls the php file that updates the database
      $.ajax({
        type: "POST",
        data: {
          action: chosenAction,
          postId: clickedPostID
        },
        url: "posts/post-modify.inc.php",
        success: data => {
          console.log(data)

          result = JSON.parse(data)

          const upToChange = `.postid-${clickedPostID} .post-upvote`
          const downToChange = `.postid-${clickedPostID} .post-downvote`
          //If the post was up/downvoted successfully, we update the DOM with the new value
          if (result.StatusCode == 10) {
            if (chosenAction == "upvote") {
              //Remove upvote
              if ($(e.target).hasClass("voted")) {
                likes--
                $(upToChange).removeClass("voted")
              }

              //Remove downvote and add upvote
              else if (
                $(e.target)
                  .siblings(".post-downvote")
                  .hasClass("voted")
              ) {
                likes += 2
                $(upToChange).addClass("voted")
                $(downToChange).removeClass("voted")
              }

              //Add upvote
              else {
                likes++
                $(upToChange).addClass("voted")
              }
            } else if (chosenAction == "downvote") {
              //Remove downvote
              if ($(e.target).hasClass("voted")) {
                likes++
                $(downToChange).removeClass("voted")
              }

              //Remove upvote and add downvote
              else if (
                $(e.target)
                  .siblings(".post-upvote")
                  .hasClass("voted")
              ) {
                likes -= 2
                $(downToChange).addClass("voted")
                $(upToChange).removeClass("voted")
              }

              //Add downvote
              else {
                likes--
                $(downToChange).addClass("voted")
              }
            }

            //Changes the like number in the DOM
            $(upToChange)
              .siblings(".post-votes")
              .html(likes)
          } else {
            console.log(result.ErrorMsg)
          }
        },
        error: err => {
          console.log(err)
        }
      })
    }
  })
})
