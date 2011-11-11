/* Author: @andmag
 */

updateSizes();
RESS.SSCapabilities();


if (navigator.cookieEnabled) {
    $("#cookies-enabled").text("enabled");
}

if (Modernizr.fontface) {
    $("#fontface-detection").text("supported");
}
if (Modernizr.canvas) {
    $("#canvas-detection").text("supported");
}
if (Modernizr.borderradius) {
    $("#borderradius-detection").text("supported");
}
if (Modernizr.csstransforms) {
    $("#csstransforms-detection").text("supported");
}
if (Modernizr.csstransforms3d) {
    $("#csstransforms3d-detection").text("supported");
}
if (Modernizr.touch) {
    $("#touch-detection").text("supported");
}


window.onorientationchange = RESS.detectSize;
window.onresize = RESS.detectSize;

function updateSizes() {
    RESS.storeSizeOfArea(".g3", "g3");
    RESS.storeSizeOfArea(".g2", "g2");
    RESS.storeSizeOfArea(".g1", "g1");
    RESS.detectSize();
}




