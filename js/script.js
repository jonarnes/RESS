/* Author: @andmag
 */


RESS.detectSize();
RESS.SSCapabilities();
RESS.storeSizeOfArea(".g3","g3");

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

$(document).ready(function() {

    var sendWidth = 320;
    if(RESS_Capas && RESS_Capas.RESS_G3W){
        sendWidth = RESS_Capas.RESS_G3W;
    }

    $.get("blocks/carousel.php?width="+sendWidth,function(data){
        $(".slider").append(data);

        $('.slidewrap2').carousel({
        slider: '.slider',
        slide: '.slide',
        /*addNav: true,*/
        addPagination: true,
        speed: 300 // ms.
    });
    });



});







