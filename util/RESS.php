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

//set capas, try to get them from cookie first
$defaultWidth = ($gridWidths["default"] ? $gridWidths["default"] : MyWurfl::get('max_image_width'));
if (MyWurfl::get('brand_name') == "generic web browser") {
    $defaultWidth = 1024;
}

$defaultWidth48 = $defaultWidth * 0.48;
$defaultWidth31 = $defaultWidth * 0.3133;

global $RESS_capas;
$RESS_capas = array(
    "viewport-width" => $defaultWidth,
    "viewport-height" => $defaultHeight,
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

        //set default rid values
        var extra = "|g1."+ RESS.ccap.vpw + "|g2." + Math.round(RESS.ccap.vpw * 0.48) + "|g3." + Math.round(RESS.ccap.vpw * 0.3133);

        d.cookie = 'RESS=' + RESS.ccap.vpw + extra + '; expires=' + ccapDate.toUTCString() + '; path=/;domain=.whateverweb.com';

        //if (console && console.log) console.log('cookie: '+ d.cookie);
    }(window, document));
</script>


