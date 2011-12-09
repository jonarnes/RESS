/* Author: @andmag
 */

updateSizes();

//RESS.SSCapabilities();


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

function updateSizes() {

    $("#screensize-detection").text(document.documentElement.clientWidth + "x" + document.documentElement.clientHeight);
    RESS.storeSizes({"g1":".g1","g2":".g2","g3":".g3"});

}

window.onorientationchange = updateSizes;
window.onresize = updateSizes;



$(window).load(function () {
    writeImgDebug();
});


function writeImgDebugImg(imgSelector, container)
    //image
    //img1-debug
{

    var newImg = new Image();
    var src = $(imgSelector).attr("src");
    console.log(src);
    newImg.src = src;
    var height = newImg.height;
    var width = newImg.width;
    console.log ('The image size is '+width+'*'+height);

    $(imgSelector + "-debug").html("Real image size: " + width + "x" + height + "<br/>container size: " + $(container).css("width") + " x " + $(container).css("height"));
}

function writeImgDebug(){
    writeImgDebugImg("#img1", ".g1 .image");
    writeImgDebugImg("#img2", ".g2 .image");
    writeImgDebugImg("#img3", ".g3 .image");
}





