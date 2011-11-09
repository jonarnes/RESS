RESS = {};

RESS.storeSizeOfArea = function(selector, name) {

    var width = $(selector).css("width");
    if(width.indexOf("px") > 0){
        width = width.replace("px","");
    }

    
    if($.cookie('RESS_'+name) != width){
        console.log("New RESS_"+selector+", went from " +$.cookie('RESS_'+name) + " to " + width);
        $.cookie("RESS_"+name,width , { path: '/', expires: 365 });
    }

}


RESS.detectSize = function() {

    var width = document.documentElement.clientWidth;

    if($.cookie('RESS_SW') != width){
        console.log("New SW, went from " + $.cookie('RESS_SW') + " to " + width);
        $.cookie("RESS_SW",width , { path: '/', expires: 365 });
    }

    var height = document.documentElement.clientHeight;

    if($.cookie('RESS_SH') != height){
        console.log("New SH, went from " + $.cookie('RESS_SH') + " to " + height);
        $.cookie("RESS_SH",height , { path: '/', expires: 365 });
    }

    //set variable for the feature list
    $("#screensize-detection").text(width + 'x' + height);
}

RESS.SSCapabilities = function() {
    if (RESS_Capas) {

        //loop through SS capas
        $.map(RESS_Capas, function(value, index) {
        });
    }else{
        console.log("No capabilities found");
    }
}