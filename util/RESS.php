<!--Load capabilities into an global JS variable-->
<script type="text/javascript">
    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    //Set width of screen in a cookie
    (function (w, d) {

        <!--
        //set width to vpw
        var e = d.documentElement, g = d.getElementsByTagName('body')[0], vpw = w.innerWidth || e.clientWidth || g.clientWidth;
        //-->

        var existing = readCookie("RESS");
        var bp;

        //set default grid values
        console.log("existing: " + existing);
        if (existing != null) {
            console.log("existing: " + existing);
        }

        if (vpw >= 1024) {
            vpw = 1024;
            bp = "w";
        } else if (vpw >= 768) {
            bp = "m";
        } else {
            bp = "n";
        }

        // Set a cookie with the client side capabilities.
        var ccapDate = new Date()
        ccapDate.setFullYear(ccapDate.getFullYear() + 1);
        d.cookie = 'RESS=vpw.' + vpw + '|bp.' + bp + '; expires=' + ccapDate.toUTCString() + '; path=/;domain=.whateverweb.com';

    }(window, document));
</script>

<?php
/*
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
    $wurflWidth = 1440;
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
*/?><!--
<script type="text/javascript">
    var RESS_Capas = {
        'RESS_VW':<?php /*echo $RESS_capas["viewport-width"] */?>,
        'RESS_G3W':<?php /*echo $RESS_capas["g3-width"] */?>,
        'RESS_G2W':<?php /*echo $RESS_capas["g2-width"] */?>,
        'RESS_G1W':<?php /*echo $RESS_capas["g1-width"] */?>
    };
</script>-->


