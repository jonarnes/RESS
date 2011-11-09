$(window).load(function () {

    var method = "orientationchange";
    var uagent = navigator.userAgent.toLowerCase();
    if (uagent.search("android") > -1) {
        method = "resize";
    }

    if (window.orientation !== undefined) {
        $(window).bind(method, function() {
            //var orientation = $(this).attr("orientation");
            RESS.detectSize();
        });
    }

});