//Handles the signing up of new users

//Starts the jQuery
$(document).ready(function() {
  //Runs when the signup form is submitted
  $("#signup-form").submit(function(event) {
    //Prevents the submit button from redirecting to signup.inc.php
    event.preventDefault()

    //Gets the values of the form inputs
    var username = $("#signup-uname").val()
    var firstname = $("#signup-fname").val()
    var lastname = $("#signup-lname").val()
    var email = $("#signup-email").val()
    var pwd = $("#signup-pwd").val()
    var tel = $("#signup-tel").val()
    var birthday = $("#signup-birthday").val()
    var gender = $("#signup-gender").val()

    //Passes the information to the php file
    $(".pload").load("./includes/signup.inc.php", {
      username: username,
      firstname: firstname,
      lastname: lastname,
      pwd: pwd,
      email: email,
      tel: tel,
      birthday: birthday,
      gender: gender
    })
  })
})
