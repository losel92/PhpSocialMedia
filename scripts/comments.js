$(document).ready(() => {
  //Shows/Hides the submit btn for submitting a comment
  $(document).on("keyup", ".make-comment-textarea", e => {
    const currentTxt = $(e.target).val()

    if (!currentTxt.length) {
      console.log("no text")
      $(".submit-comment-btn").slideUp(300)
    } else {
      console.log("yes text")
      $(".submit-comment-btn").slideDown(500)
    }
  })
})
