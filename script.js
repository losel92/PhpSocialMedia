//jQuery Functions
function OpenModal(modalCont){

	$(modalCont).parent().fadeIn(350);
	$(modalCont).children().hide().delay(100).show(250);
} 

function CloseModal(modalCont){

	$(modalCont).parent().delay(200).fadeOut(100);
	$(modalCont).children().hide(250);
}

function DownSizePic(pic, imgW, imgH, outW, outH){

	var imgRatio = imgW / imgH;
	var postH = imgH / (imgW / outW)
	var postW = imgW / (imgH / outH)
	
	$(pic).attr("width", outW);
	$(pic).attr("height", postH);
}


//Starts the jQuery
$(document).ready(function(){

	//Picture upload
    $("#profile-pic-upload-btn").click(function(){

    	var fd = new FormData();

        var files = $('#imgfile')[0].files[0];

        fd.append('file',files);

        $.ajax({ //Ajax request
            url:'imgUpload.php',
            type:'post',
            data:fd,
            contentType: false,
            processData: false,
            success:function(response){
                if(response != 0){ 
                	//If the upload was successful, it will 
                	//assign the src of the new img to the DOM element and properly downsize it
                	//Also, makes a cool animation ;D
                	$('.img-preview').slideUp(1000);
                	setTimeout(function(){
                	$("#img-crop-prev").delay(1500).attr("src",response);
                    $('.jcrop-holder').delay(1500).find('img').attr('src', response);
                    img.src = response;
                    DownSizePic('#img-crop-prev', img.width, img.height, 600, 400);
                    $('.img-preview').slideDown(1000);
                	}, 1000);
                }
                else{
                    alert('File not uploaded');
                }
            }
		});
	});

    //downsizes the img if it is too large
	var imgPath = $("#img-crop-prev").attr('src');
	var img = new Image();
    img.src = imgPath;

    DownSizePic('#img-crop-prev', img.width, img.height, 600, 400);

   

    //Picture crop
    const jcrop = Jcrop.attach('img-crop-prev', {
    	aspectRatio: 1
    });

    $(".jcrop-image-stage").click(function(){
    	$("#img-crop-btn").slideDown(200);
    });
 
    $("#img-crop-btn").click(function(){
        var imgWidth = 0;
        var imgHeight = 0;

        //Creates a new img object and assigns its source to the uploaded img's
        var prevImg = new Image();
        prevImg.src = imgPath;
		prevImg.onload = function() {
			imgWidth = this.width;
			imgHeight = this.height;

			//The offset for the size of the actual saved image compared to the image displayed in the div
	    	var wOffset = imgWidth / 600;
	    	var hOffset = imgWidth / 600;

	    	var size = { //The translated values that will be passed to the php script
	    		w: canvasSize.w * wOffset,
	    		h: canvasSize.h * hOffset,
	    		x: canvasSize.x * wOffset,
	    		y: canvasSize.y * hOffset
	    	}


	        var croppedImgPath = 'image-crop.php?x='+size.x+'&y='+size.y+'&w='+size.w+'&h='+size.h+'&img='+imgPath;

	        //
	        $.ajax({
                type: "POST",
                url: "imgUpload.php" ,
                data: { croppedPath: croppedImgPath },
                success : function() { 
                	location.reload();
                }
            });
		}
    });

});