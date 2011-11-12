<?php

$RESSCookie = $_COOKIE['RESS'];
if ($RESSCookie) {
    $RESSValues = explode('|', $RESSCookie);
    $gridWidths;
    $i = 0;
    foreach ($RESSValues as $RESSValue) {
        if ($i == 0) {
            $gridWidths["default"] = $RESSValue;
        } else {
            $grids = explode('.', $RESSValue);
            $gridWidths[$grids[0]] = $grids[1];
        }
        $i++;
    }
}

$wurflWidth = MyWurfl::get('max_image_width');
if (MyWurfl::get('brand_name') == "generic web browser") {
    $wurflWidth = 1024;
}

//set capas, try to get them from cookie first
$defaultWidth = ($gridWidths["default"] ? $gridWidths["default"] : $wurflWidth);


$defaultWidth48 = $defaultWidth * 0.48;
$defaultWidth31 = $defaultWidth * 0.3133;

global $RESS_capas;
$RESS_capas = array(
    "viewport-width" => $defaultWidth,
    "g3-width" => ($gridWidths["g3"] ? $gridWidths["g3"] : $defaultWidth31),
    "g2-width" => ($gridWidths["g2"] ? $gridWidths["g2"] : $defaultWidth48),
    "g1-width" => ($gridWidths["g1"] ? $gridWidths["g1"] : $defaultWidth)
);
?>


<!--Load capabilities into an global JS variable-->
<script type="text/javascript">
    var RESS_Capas = {
        'RESS_VW':<?php echo $RESS_capas["viewport-width"] ?>,
        'RESS_G3W':<?php echo $RESS_capas["g3-width"] ?>,
        'RESS_G2W':<?php echo $RESS_capas["g2-width"] ?>,
        'RESS_G1W':<?php echo $RESS_capas["g1-width"] ?>
    };


    function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
    //Set width of screen in a cookie
    (function(w, d) {
        var RESS = w.RESS = w.RESS || {};
        // Client side detected capabilities.
        // Only the ones needed right away for responsive images and such.
        RESS.ccap = RESS.ccap || {};
        if (w.document.documentElement.clientWidth) RESS.ccap.vpw = w.document.documentElement.clientWidth;

        // Set a cookie with the client side capabilities.
        var ccapDate = new Date()
        ccapDate.setFullYear(ccapDate.getFullYear() + 1);

        var existing = readCookie("RESS");
        //set default grid values
        console.log("existing: " + existing);
        if(existing == null){
            var g1 = RESS.ccap.vpw;
            //1%
            var oneP =  Math.round(g1*0.01);
            var vpw = g1;

            //body is 78% of maxwidth
            if(vpw >= 768){
                g1=Math.round(g1*0.78);
            }else{
                g1=Math.round(g1*0.92);
            }
            var g2 = vpw <= 768 ? g1 : Math.round(g1 * 0.48);
            var g3 = vpw <= 768 ? g1 : (vpw >= 1100 ? Math.round(g1 * 0.331) : Math.round(g1 * 0.48));

            //remove 1% padding on each side + 1% padding on each side of the container = 4%
            g1 = g1 - (oneP*4);
            g2 = g2 - (oneP*4);
            g3 = g3 - (oneP*4);

            var extra = "|g1."+ g1 + "|g2." + g2 + "|g3." + g3;
            console.log("extra: " + extra);
            d.cookie = 'RESS=' + RESS.ccap.vpw + extra + '; expires=' + ccapDate.toUTCString() + '; path=/;domain=.whateverweb.com';
        }
        //if (console && console.log) console.log('cookie: '+ d.cookie);
    }(window, document));
</script>


