/*document.onkeydown = function(e) {
    if (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 85 || e.keyCode === 117)) {}
    if (e.shiftKey && (event.button == 2 || event.button == 3)) {}
    return false;
};
function click() {
    if (event.button == 2 || event.button == 3) {
        oncontextmenu = 'return false';
        function disableWheelScroll() {
            if (document.body.addEventListener) document.body.addEventListener('DOMMouseScroll', blockWheel, false);
            document.body.onmousewheel = blockWheel;
        }
        function blockWheel(event) {
            if (!event) event = window.event;
            if (event.stopPropagation) event.stopPropagation();
            else event.cancelBubble = true;
            if (event.preventDefault) event.preventDefault();
            else event.returnValue = false;
        }
        disableWheelScroll();
    }
}
document.onmousedown = click
document.oncontextmenu = new Function("return false;")*/
//	document.oncontextmenu = new Function("return true;")
// Here we will detect any form of addons used to open chrome developer tools


// Console warning XSS
const warningTitleCSS = 'color:red; font-size:60px; font-weight: bold; -webkit-text-stroke: 1px black;';
const warningDescCSS = 'font-size: 18px;';

console.log('%cStop!', warningTitleCSS);
console.log("%cThis is a browser feature intended for developers. If someone told you to copy and paste something here to enable a feature or \"hack\" someone's account, it is a scam and will give them access to your account.", warningDescCSS);
console.log('%cSee https://www.sickness.cc/selfxss for more information.', warningDescCSS);