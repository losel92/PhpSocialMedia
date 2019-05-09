//Starts the jQuery
$(document).ready(function(){

    $("#profile-pic-upload-btn").click(function(){

    	var fd = new FormData();

        var files = $('#imgfile')[0].files[0];

        fd.append('file',files);

        $.ajax({
            url:'imgUpload.php',
            type:'post',
            data:fd,
            contentType: false,
            processData: false,
            success:function(response){
                if(response != 0){
                    $("#img-crop-prev").attr("src",response);
                    $('.img-preview').show();
                    console.log(response);
                }
                else{
                    alert('File not uploaded');
                }
            }
		});
	});
});

//jQuery Functions
function OpenModal(modalCont){
	$(modalCont).parent().fadeIn(350);
	$(modalCont).children().hide().delay(100).show(250);
}
function CloseModal(modalCont){
	$(modalCont).parent().delay(200).fadeOut(100);
	$(modalCont).children().hide(250);
}