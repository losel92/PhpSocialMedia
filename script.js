//Starts the jQuery
$(document).ready(function(){

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