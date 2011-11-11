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
    $("#screensize-detection").text(document.documentElement.clientWidth + "x" + document.documentElement.clientHeight);
    RESS.storeSizes({"g1":".g1","g2":".g2","g3":".g3"});

}

$(window).load(function () {
    writeImgDebug();
});


function writeImgDebug()
    //image
    //img1-debug
{

    var newImg = new Image();
    var src = $("#img1").attr("src");
    console.log(src);
    newImg.src = src;
    var height = newImg.height;
    var width = newImg.width;
    console.log ('The image size is '+width+'*'+height);

    $("#img1-debug").html("Real image size: " + width + "x" + height + "<br/>container size: " + $(".g3 .image").css("width") + " x " + $(".g3 .image").css("height"));
}




