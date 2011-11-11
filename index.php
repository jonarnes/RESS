<?php require_once dirname(__FILE__).'/SS/MyWurfl.php'; ?>
<?php include 'blocks/html-head.php' ?>
<body>
<div id="container">
    <header>
        <div class="g1">
            <h1>RESS<span class="hide-small"> - RWD &amp; Server Side Components</span></h1>
            <noscript>
                <h3>Why you have JavaScript is disabled!? Well we're serving you a site based only server side capabilities then. </h3>
            </noscript>
        </div>
    </header>
    <div id="main" role="main">
        <div class="cf"></div>
        <div id="content">
            <h2>Responsive Images</h2>
            <p>This site uses a RESS approach for serving images in an Responsive Web Design. That means that we
                combine server and client technologies in order to get perfectly scaled images.</p>
            <h2>Testing for capabilities</h2>
            <div class="g3">
                <div class="feature-header">
                    <img src="images/astronaut.png"/>
                    <h3>Feature detection</h3>
                    <div class="cf"></div>
                </div>
                <ul class="feature-list">
                    <li>Cookies: <span class="feature-value" id="cookies-enabled">disabled</span></li>
                    <li>Viewport size: <span class="feature-value" id="screensize-detection">not supported</span></li>
                </ul>
            </div>
            <div class="g3">
                <div class="feature-header">
                    <img src="images/viking.jpg"/>
                    <h3>Serverside detection</h3>
                    <div class="cf"></div>
                </div>
                <ul class="feature-list">
                    <li>Detected device: <span class="feature-value"><?php echo MyWurfl::get('brand_name').' '.MyWurfl::get('model_name') ?></span></li>
                    <li>Detected viewport size: <span class="feature-value"><?php echo MyWurfl::get('max_image_width').'x'.MyWurfl::get('max_image_height') ?></span></li>
                    <li>Detected resolution width: <span class="feature-value"><?php echo MyWurfl::get('resolution_width').'x'.MyWurfl::get('resolution_height') ?></span></li>
                </ul>
            </div>

            <div class="g3">
                <div class="feature-header">
                    <img src="images/yoda.jpg"/>
                    <h3>RESS info</h3>
                    <div class="cf"></div>
                </div>
                <ul>
                    <li>Viewport size: <?php echo $RESS_capas["viewport-width"]?></li>
                    <li>G1 grid width: <?php echo $RESS_capas["g1-width"];?></li>
                    <li>G2 grid width: <?php echo $RESS_capas["g2-width"];?></li>
                    <li>G3 grid width: <?php echo $RESS_capas["g3-width"];?></li>
                </ul>
            </div>
            <div class="cf"></div>

            <h2>Test Images</h2>
            <div class="g3 grid">
                <h3>G3</h3>
                <div id="img1-debug"></div>
            	<div class="image">
                    <img id="img1" src="http://whateverweb.com/dd/cimg/gg3/http://farm3.static.flickr.com/2648/4093575863_9ba39f1a07_b.jpg"/>
                </div>
             </div>
            <div class="g3 grid">
                <h3>G3</h3>
            	<div class="image">
                    <img src="http://whateverweb.com/dd/cimg/gg3/http://farm3.static.flickr.com/2648/4093575863_9ba39f1a07_b.jpg"/>
                </div>
             </div>
            <div class="g3 grid">
                <h3>G3</h3>
            	<div class="image">
                    <img src="http://whateverweb.com/dd/cimg/gg3/http://farm3.static.flickr.com/2648/4093575863_9ba39f1a07_b.jpg"/>
                </div>
             </div>
            <div class="g2 grid">
                <h3>G2</h3>
            	<div class="image">
                    <img src="http://whateverweb.com/dd/cimg/gg2/http://farm3.static.flickr.com/2648/4093575863_9ba39f1a07_b.jpg"/>
                </div>
             </div>
            <div class="g2 grid">
                <h3>G2</h3>
            	<div class="image">
                    <img src="http://whateverweb.com/dd/cimg/gg2/http://farm3.static.flickr.com/2648/4093575863_9ba39f1a07_b.jpg"/>
                </div>
             </div>
            <div class="g1 grid">
                <h3>G1</h3>
            	<div class="image">
                    <img src="http://whateverweb.com/dd/cimg/gg1/http://farm3.static.flickr.com/2648/4093575863_9ba39f1a07_b.jpg"/>
                </div>
             </div>
        </div>
    </div>
    <div class="cf"></div>
<small>Photos <a href="http://www.flickr.com/photos/laszlo-photo/">http://www.flickr.com/photos/laszlo-photo/</a></small>

</div>
<footer class="">
        <small>2011 <span class="license">Created by <a href="http://about.me/ama">Anders Magnus Andersen</a> - <a href="http://twitter.com/andmag">@andmag</a><br/>
            With help from: <a href="http://www.modernizr.com/">Modernizr</a> &amp; WURFL from <a href="http://www.scientiamobile.com/">ScientiaMobile</a> &amp; <a href="http://jquery.com/">jQuery</a> &amp; <a href="http://html5boilerplate.com/">HTML5 Boilerplate</a> &amp; <a href="http://typekit.com">Typekit</a> &amp; Dynamix-carousel plugin by <a href="https://github.com/Wilto/Dynamic-Carousel">Mat Marquis (@wilto)</a>
            </small>
    </footer>
<!--! end of #container -->
</body>
<?php include 'blocks/html-foot.php' ?>