function showForm(choice, form){
	if (choice == "class") {
		document.getElementByClass(form).style.display = "block";
	}
	else if(choice == "id"){
		document.getElementById(form).style.display = "block";
	}
}

function hideForm(choice, form){
	if (choice == "class") {
		document.getElementByClass(form).style.display = "none";
	}
	else if(choice == "id"){
		document.getElementById(form).style.display = "none";
	}
}