$(document).ready(() => {
  //when the user submits the form pressing enter
  $(document).on("submit", "#searchForm", e => {
    e.preventDefault()
    const searchTxt = $(e.target)
      .find("#searchTxt")
      .val()

    searchForUsers(searchTxt)
    searchForPosts(searchTxt)
  })
  $(document).on("click", "#searchSubmit", e => {
    const searchTxt = $(e.target)
      .siblings("#searchTxt")
      .val()

    searchForUsers(searchTxt)
    searchForPosts(searchTxt)
  })

  //Switches between posts and users
  $(document).on("click", "#search-posts-btn, #search-users-btn", e => {
    //Highlights the clicked btn
    $(e.target).addClass("btn-selected")
    $(e.target)
      .siblings()
      .removeClass("btn-selected")

    //Switches tabs to posts
    if ($(e.target).attr("id") == "search-posts-btn") {
      $(".search-result-users").hide()
      $(".search-result-posts").fadeIn(300)
    }
    //Switches tabs to users
    else if ($(e.target).attr("id") == "search-users-btn") {
      $(".search-result-posts").hide()
      $(".search-result-users").fadeIn(300)
    }
  })
})

//Clicking the submit div
function searchForPosts(searchTxt) {
  //Searches through posts
  $.ajax({
    type: "POST",
    url: "search/search.php",
    data: {
      action: "searchPosts",
      text: searchTxt
    },
    success: function(res) {
      //Updates the DOM element with the new data
      $(".search-result-posts").html(res)

      //If the posts tab is selected, it will do a fadeIn animation
      if ($("#search-posts-btn").hasClass("btn-selected")) {
        $(".search-result-posts")
          .hide()
          .fadeIn(300)
      }
    }
  })
}

function searchForUsers(searchTxt) {
  //Searches through users
  $.ajax({
    type: "POST",
    url: "search/search.php",
    data: {
      action: "searchUsers",
      text: searchTxt
    },
    success: function(res) {
      //Updates the DOM element with the new data
      $(".search-result-users").html(res)

      //If the users tab is selected, it will do a fadeIn animation
      if ($("#search-users-btn").hasClass("btn-selected")) {
        $(".search-result-users")
          .hide()
          .fadeIn(300)
      }
    }
  })
}
