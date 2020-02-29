$(document).ready(() => {
  $(document).on("submit", "#searchForm", e => {
    e.preventDefault()
    const searchTxt = $(e.target)
      .find("#searchTxt")
      .val()

    //Searches through posts
    $.ajax({
      type: "POST",
      url: "search/search.php",
      data: {
        action: "searchPosts",
        text: searchTxt
      },
      success: function(res) {
        $(".search-result-posts")
          .html(res)
          .hide()
          .fadeIn()
      }
    })

    //Searches through users
    $.ajax({
      type: "POST",
      url: "search/search.php",
      data: {
        action: "searchUsers",
        text: searchTxt
      },
      success: function(res) {
        $(".search-result-users")
          .html(res)
          .hide()
          .fadeIn()
      }
    })
  })
})
