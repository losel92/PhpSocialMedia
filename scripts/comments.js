$(document).ready(() => {
  let newCommentTxt = ""

  //Fetches all comments for a given post when the post is opened
  $(document).on("click", ".post-comments", e => {
    //Gets the id of the opened post
    const postId = $(e.target)
      .parents(".userPost")
      .attr("postid")

    //The comment section jquery object
    const commentSection = $(e.target)
      .parents(".userPost")
      .find(".post-comments-comments")

    //How many comments are already loaded
    const commentCount = $(commentSection).children().length

    //If no comments are loaded, load them
    if (!commentCount) {
      $.ajax({
        type: "POST",
        url: "./posts/postComments.php",
        data: {
          action: "getComments",
          postId: postId
        },
        success: function(res) {
          if (res) {
            $(commentSection).show()
            $(commentSection).append(res)
          }
        }
      })
    }
  })

  //Shows/Hides the submit btn for submitting a comment
  $(document).on("keyup", ".make-comment-textarea", e => {
    const currentTxt = $(e.target).val()

    if (!currentTxt.length) {
      console.log("no text")
      $(".submit-comment-btn").slideUp(300)
    } else {
      console.log("yes text")
      $(".submit-comment-btn").slideDown(500)

      newCommentTxt = currentTxt
    }
  })

  //Post/submit a comment
  $(document).on("click", ".submit-comment-btn", e => {
    //Gets the id of the current post the comment will be connected to
    const postId = $(e.target)
      .parents(".userPost")
      .attr("postid")

    //Post call to post_comment
    $.ajax({
      type: "POST",
      url: "./posts/postComments.php",
      data: {
        action: "createComment",
        postId: postId,
        content: newCommentTxt
      },
      success: res => {
        console.log(res)

        //The comment section jquery object
        const commentSection = $(e.target)
          .parents(".userPost")
          .find(".post-comments-comments")

        if (res) {
          //Inserts the new comment into the DOM
          $(commentSection).show()
          $(commentSection).prepend(res)
          $(commentSection)
            .children()
            .eq(0)
            .hide()
            .fadeIn(500)
        }
      },
      error: err => {
        alert(err)
      }
    })
  })

  //Up/downvotes a comment
  $(document).on("click", ".comment-upvote, .comment-downvote", e => {
    let action

    //Determines whether the user pressed the upvote or downvote btn
    if ($(e.target).hasClass("comment-upvote")) action = "upvote"
    else if ($(e.target).hasClass("comment-downvote")) action = "downvote"

    //Gets the id of the comment being upvoted
    const commentId = $(e.target)
      .parents(".single-comment")
      .attr("commentid")

    $.ajax({
      type: "POST",
      url: "./posts/postComments.php",
      data: {
        action: action,
        commentId: commentId
      },
      success: function(res) {
        console.log(res)

        result = JSON.parse(res)

        let likes = parseInt(
          $(e.target)
            .siblings(".comment-votes")
            .html()
        )

        const upToChange = `.comment-${commentId} .comment-upvote`
        const downToChange = `.comment-${commentId} .comment-downvote`
        //If the post was up/downvoted successfully, we update the DOM with the new value
        if (result.StatusCode == 10) {
          if (action == "upvote") {
            //Remove upvote
            if ($(e.target).hasClass("voted")) {
              likes--
              $(upToChange).removeClass("voted")
            }

            //Remove downvote and add upvote
            else if (
              $(e.target)
                .siblings(".comment-downvote")
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
          } else if (action == "downvote") {
            //Remove downvote
            if ($(e.target).hasClass("voted")) {
              likes++
              $(downToChange).removeClass("voted")
            }

            //Remove upvote and add downvote
            else if (
              $(e.target)
                .siblings(".comment-upvote")
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
            .siblings(".comment-votes")
            .html(likes)
        } else {
          console.log(result.ErrorMsg)
        }
      }
    })
  })
})
