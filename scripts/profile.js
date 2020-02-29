/// Functions and handlers for changes in the profile section

function DownSizePic(pic, imgW, imgH, outW, outH) {
  var imgRatio = imgW / imgH
  var postH = imgH / (imgW / outW)
  var postW = imgW / (imgH / outH)

  $(pic).attr("width", outW)
  $(pic).attr("height", postH)
}

var jcrop_api
var img
var imgPath
var canvasSize
function ApplyCrop(image, width) {
  //downsizes the img to fit inside the container
  imgPath = $(image).attr("src")
  img = new Image()
  img.src = imgPath
  DownSizePic(image, img.width, img.height, width, 400)

  //Assigns the picture to the Jcrop element
  $(image).Jcrop(
    {
      aspectRatio: 1,
      minSize: [100, 100],
      onSelect: updateCoords,
      addClass: "jcrop-centered"
    },
    function() {
      jcrop_api = this
    }
  )
}

function updateCoords(c) {
  $("#x").val(c.x)
  $("#y").val(c.y)
  $("#w").val(c.w)
  $("#h").val(c.h)
  canvasSize = { x: c.x, y: c.y, w: c.w, h: c.h }
}

//Checks if the user has selected an area to crop
function checkCoords() {
  if (parseInt(jQuery("#w").val()) > 0) return true
  alert("Please select a region to be cropped first")
  return false
}

//Starts the jQuery
$(document).ready(function() {
  //Profile info logo animation
  $(".profile-column").mouseenter(function() {
    $("#profile-edit-logo").fadeIn(200)
  })
  $(".profile-column").mouseleave(function() {
    $("#profile-edit-logo").fadeOut(200)
  })

  //Profile info edit
  $("#profile-edit-logo").click(function() {
    OpenModal("#info-edit-form-container")
  })
  $("#info-edit-submit").click(function() {
    var fname = $("#info-edit-fname").val()
    var lname = $("#info-edit-lname").val()
    var email = $("#info-edit-email").val()
    var bday = $("#info-edit-bday").val()
    console.log("we got here")

    $.ajax({
      //Ajax request
      type: "POST",
      url: "./includes/profile-info-edit.inc.php",
      data: { fname: fname, lname: lname, email: email, bday: bday },
      success: function(status) {
        if (status == 1) {
          location.reload()
        } else {
          console.log(status)
        }
      }
    })
  })

  //Picture upload
  $("#profile-pic-upload-btn").click(function() {
    var fd = new FormData()

    var files = $("#imgfile")[0].files[0]

    //Will only execute if the user has chosen a file
    if (files) {
      fd.append("file", files)

      $.ajax({
        //Ajax request
        url: "./imgUpload.php",
        type: "post",
        data: fd,
        contentType: false,
        processData: false,
        success: function(response) {
          if (response != 0) {
            //If the upload was successful, it will
            //assign the src of the new img to the DOM element and properly downsize it
            //Also, makes a cool animation ;D
            jcrop_api.destroy()
            $("#img-crop-btn").fadeOut()
            $(".img-preview").slideUp(1000)

            setTimeout(function() {
              $("#img-crop-prev").remove()
              $(".img-preview").append(
                "<img src='" + response + "' id='img-crop-prev'>"
              )
              ApplyCrop("#img-crop-prev", 600)
              $(".img-preview").css("display", "block")
            }, 1500)
          } else {
            alert("File not uploaded")
          }
        }
      })
    }
  })

  //Makes the img "croppable"
  ApplyCrop("#img-crop-prev", 600)

  var imgToCrop = $(".jcrop-holder").find("img")

  //when the user starts cropping, the 'CROP' button will appear
  $(".img-preview").click(function() {
    $("#img-crop-btn").slideDown(200)
  })

  //Will crop the image and upload it to the server
  $("#img-crop-btn").click(function() {
    //The scrip will only run if the user has selected an area to crop
    if (checkCoords()) {
      var imgWidth = 0
      var imgHeight = 0

      //Creates a new img object and assigns its source to the uploaded img's
      var prevImg = new Image()
      prevImg.src = imgPath
      prevImg.onload = function() {
        imgWidth = this.width
        imgHeight = this.height

        //The offset for the size of the actual saved image compared to the image displayed in the div
        var wOffset = imgWidth / 600
        var hOffset = imgWidth / 600

        var size = {
          //The translated values that will be passed to the php script
          w: canvasSize.w * wOffset,
          h: canvasSize.h * hOffset,
          x: canvasSize.x * wOffset,
          y: canvasSize.y * hOffset
        }

        var croppedImgPath =
          "image-crop.php?x=" +
          size.x +
          "&y=" +
          size.y +
          "&w=" +
          size.w +
          "&h=" +
          size.h +
          "&img=" +
          imgPath

        //Calls the php file to upload the img
        $.ajax({
          type: "POST",
          url: "./imgUpload.php",
          data: { croppedPath: croppedImgPath },
          success: function() {
            console.log("Cropped img path: " + croppedImgPath)
            location.reload()
          }
        })
      }
    }
  })
})
