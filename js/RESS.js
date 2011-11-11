RESS = {};


RESS.storeSizes = function(values) {
    var gridValues = "";
    for(var name in values){
        if(name != "undefined"){
            var width = $(values[name]).css("width").replace("px","");
            gridValues+= "|" + name + "." + Math.round(width);
        }
    }
    //remove first |
    gridValues = gridValues.substr(1);

      console.log("GridValues: " + gridValues);
    //Set new cookie with RESS values

    var ccapDate = new Date();
    ccapDate.setFullYear(ccapDate.getFullYear() + 1);
    document.cookie = 'RESS='+ document.documentElement.clientWidth + "|" + gridValues + '; expires='+ ccapDate.toUTCString() +'; path=/;domain=.whateverweb.com';

}



/*RESS.detectSize = function() {

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
}*/

RESS.SSCapabilities = function() {
    if (RESS_Capas) {

        //loop through SS capas
        $.map(RESS_Capas, function(value, index) {
        });
    }else{
        console.log("No capabilities found");
    }
}