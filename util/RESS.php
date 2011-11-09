<?php
        //set capas, try to get them from cookie first

         $defaultWidth = ($_COOKIE['RESS_SW'] ? $_COOKIE['RESS_SW'] : MyWurfl::get('max_image_width'));
         $defaultHeight = ($_COOKIE['RESS_SH'] ? $_COOKIE['RESS_SH'] : MyWurfl::get('max_image_height'));

        global $RESS_capas;
        $RESS_capas = array(
            "viewport-width"=>$defaultWidth,
            "viewport-height"=>$defaultHeight,
            "g3-width"=>($_COOKIE['RESS_g3'] ? $_COOKIE['RESS_g3'] : $defaultWidth)
        );
?>


    <!--Load capabilities into an global JS variable-->
        <script type="text/javascript">
            var RESS_Capas = {
            'RESS_VW':<? echo $RESS_capas["viewport-width"] ?>,
            'RESS_VH':<? echo $RESS_capas["viewport-height"] ?>,
            'RESS_G3W':<? echo $RESS_capas["g3-width"] ?>
            };


    //Set width of screen in a cookie
    (function(w, d){
        var RESS = w.RESS = w.RESS || {};
        // Client side detected capabilities.
        // Only the ones needed right away for responsive images and such.
        RESS.ccap = RESS.ccap || {};
        if (w.document.documentElement.clientWidth) RESS.ccap.vpw = w.document.documentElement.clientWidth;

        // Set a cookie with the client side capabilities.
        var ccapDate = new Date()
        ccapDate.setFullYear(ccapDate.getFullYear() + 1);
        d.cookie = 'RESS='+ RESS.ccap.vpw + '; expires='+ ccapDate.toUTCString() +'; path=/';

        //if (console && console.log) console.log('cookie: '+ d.cookie);
    }(window, document));
</script>


