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


window.onorientationchange = RESS.updateSizes;
window.onresize = RESS.updateSizes;

function updateSizes() {
    RESS.storeSizes({"g1":".g1","g2":".g2","g3":".g3"});

}




