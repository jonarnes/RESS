<?php require_once dirname(__FILE__).'/SS/MyWurfl.php'; ?>
<?php include 'blocks/html-head.php' ?>
<body>
<?php include 'util/RESS.php' ?>
<div id="container">
    <header>
        <div class="g3">
            <h1>RESS</h1>
            <h5>Responsive Design & Server Side components</h5>
            <noscript>
                <h6>Oh, oh... Javascript is disabled...</h6>
            </noscript>
        </div>
    </header>
    <div id="main" role="main">
        <div class="cf"></div>
        <div id="content">
            <div class="g2">
                <div class="feature-header">
                    <img src="images/astronaut.png"/>
                    <h4>Feature detection</h4>
                    <h6>Using Modernizr</h6>
                    <div class="cf"></div>
                </div>
                <ul class="feature-list">
                    <li>Touch: <span class="feature-value" id="touch-detection">not supported</span></li>
                    <li>Fontface: <span class="feature-value" id="fontface-detection">not supported</span></li>
                    <li>Canvas: <span class="feature-value" id="canvas-detection">not supported</span></li>
                    <li>Borderradius: <span class="feature-value" id="borderradius-detection">not supported</span></li>
                    <li>CSS transforms: <span class="feature-value" id="csstransforms-detection">not supported</span></li>
                    <li>CSS transforms 3d: <span class="feature-value" id="csstransforms3d-detection">not supported</span></li>
                    <li>Screen size: <span class="feature-value" id="screensize-detection">not supported</span></li>

                </ul>
            </div>
            <div class="g2">
                <div class="feature-header">
                    <img src="images/viking.jpg"/>
                    <h4>Serverside detection</h4>
                    <h6>Using WURFL cloud service from PHP</h6>
                    <div class="cf"></div>
                </div>
                <ul class="feature-list">
                    <li>Brand and model name: <span class="feature-value"><?php echo MyWurfl::get('brand_name').' '.MyWurfl::get('model_name') ?></span></li>
                    <li>Pointing method: <span class="feature-value"><?php echo MyWurfl::get('pointing_method') ?></span></li>
                    <li>Has qwerty keyboard: <span class="feature-value"><?php echo MyWurfl::get('has_qwerty_keyboard') ?></span></li>
                    <li>Viewport size: <span class="feature-value"><?php echo MyWurfl::get('max_image_width').'x'.MyWurfl::get('max_image_height') ?></span></li>
                    <li>Screen resolution: <span class="feature-value"><?php echo MyWurfl::get('resolution_width').'x'.MyWurfl::get('resolution_height') ?></span></li>
                    <li>Supports iFrame: <span class="feature-value"><?php echo MyWurfl::get('xhtml_supports_iframe') ?></span></li>
                </ul>
            </div>

            <div class="g3">
                <h3>Combining FD and SS image size</h3>
                <p>SS will be used on first load. Then, FD capabilities will be stored in a cookie and used on the next load.</p>
                <ul>
                    <li>Viewport size: <?php echo $RESS_capas["viewport-width"]."x".$RESS_capas["viewport-height"];?></li>
                    <li>Max carousel area width: <?php echo $RESS_capas["g3-width"];?></li>
                </ul>
            </div>
            <div id="carousel" class="g3">
                <h3>Carousel demo using responsive images</h3>
            	<div class="slidewrap2">
                    <ul class="slider">
                        <li class="slide">
                            <img src="http://imageserver.mobiletech.no/img/?width=<?php echo $RESS_capas["g3-width"] ?>&src=http://farm4.static.flickr.com/3340/3270356872_f6fc09d364_b.jpg"/>
                        </li>
                    </ul>
                </div>
                <small>Photos by <a href="http://www.flickr.com/photos/franciscoantunes/">Fr Antunes</a></small>
             </div>

        </div>
        <div class="g3">
            <h3>iFrame</h3>
            <?php if(MyWurfl::get('xhtml_supports_iframe') == "full"){ ?>
                <div class="fb-like" data-href="localhost:1338" data-send="false" data-layout="box_count" data-width="50" data-show-faces="true"></div>
            <?php }else{ ?>
                <div>Sorry, no FaceBook for you my friend.</div>
            <?php }?>
        </div>
    </div>
    <div class="cf"></div>


</div>
<footer class="">
        <small>2011 <span class="license">Created by <a href="http://about.me/ama">Anders Magnus Andersen</a> - <a href="http://twitter.com/andmag">@andmag</a><br/>
            With help from: <a href="http://www.modernizr.com/">Modernizr</a> &amp; WURFL from <a href="http://www.scientiamobile.com/">ScientiaMobile</a> &amp; <a href="http://jquery.com/">jQuery</a> &amp; <a href="http://html5boilerplate.com/">HTML5 Boilerplate</a> &amp; <a href="http://typekit.com">Typekit</a> &amp; Dynamix-carousel plugin by <a href="https://github.com/Wilto/Dynamic-Carousel">Mat Marquis (@wilto)</a>
            </small>
    </footer>
<!--! end of #container -->
</body>
<?php include 'blocks/html-foot.php' ?>