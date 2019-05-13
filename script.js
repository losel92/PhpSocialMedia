//jQuery Functions
function OpenModal(modalCont){
	$(modalCont).parent().fadeIn(350);
	$(modalCont).children().hide().delay(100).show(250);
}
function CloseModal(modalCont){
	$(modalCont).parent().delay(200).fadeOut(100);
	$(modalCont).children().hide(250);
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
                    $("#img-crop-prev").attr("src",response);
                    $('.jcrop-holder').find('img').attr('src', response);
                    $('.img-preview').show();
                }
                else{
                    alert('File not uploaded');
                }
            }
		});
	});

    if ($('#img-crop-prev')){

    }

    //Picture crop
    Jcrop.attach('img-crop-prev');
 
    $("#img-crop-btn").click(function(){
        var imgPath = $("#img-crop-prev").attr('src');
        var imgWidth = 0;
        var imgHeight = 0;

        //Creates a new img object and assigns its source to the uploaded img's
        var img = new Image();
        img.src = imgPath;
		img.onload = function() {
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