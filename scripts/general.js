/// General global functions to be used accross the system

//takes a modal id or tag and opens it
function OpenModal(modalCont) {
  $(modalCont)
    .parent()
    .fadeIn(350)
  $(modalCont)
    .children()
    .hide()
    .delay(100)
    .show(250)
}

//takes a modal id or tag and closes it
function CloseModal(modalCont) {
  $(modalCont)
    .parent()
    .delay(200)
    .fadeOut(100)
  $(modalCont)
    .children()
    .hide(250)
}
