<?php require_once dirname(__FILE__) . '/SS/MyWurfl.php'; ?>
<?php include 'blocks/html-head.php' ?>
<body>
<div id="container">
    <header>
        <div class="g1">
            <h1>RESS<span class="hide-small"> - RWD &amp; Server Side Components</span></h1>
            <noscript>
                <h3>Why you have JavaScript is disabled!? Well we're serving you a site based only server side
                    capabilities then. </h3>
            </noscript>
        </div>
    </header>
    <div id="main" role="main">
        <div class="cf"></div>
        <div id="content">
            <h2>Responsive Images</h2>

            <p>This site uses a RESS approach for serving images in an Responsive Web Design. That means that we
                combine server and client technologies in order to get perfectly scaled images. </p>

            <p>The site has 3 breakpoints.</p>
            <ul>
                <li>Narrow (up to 768px width)</li>
                <li>medium (up to 1024px)</li>
                <li>Wide (above 1024px)</li>
            </ul>
            <p>
                We use 2 techniques:
            </p>
            <ol>
                <li>We set a cookie in head that says something about viewport size + the current breakpoint. Example:
                    Viewport width = 1024 and breakpoint = wide.
                </li>
                <li>We have an image server that can scale images based on the size that are set in the cookie and also
                    have fallback values if cookies or javascript is not available.
                </li>
            </ol>
            <p>
                Example of the image server URL:<br/>
                <strong>http://whateverweb.com/img/vpw_1024/bp_w/pc/w_31/m_48/n_98/http://farm3.staticflickr.com/2702/4346062272_8b4a4a18cc_b.jpg</strong>
            </p>
            <ul>
                <li><strong>vpw_1024</strong> = the default width if nothing is set in the cookie</li>
                <li><strong>bp_w</strong> = the default breakpoint if nothing is set in the cookie</li>
                <li><strong>w_31</strong> = the image should be 31% of the screen size in the wide breakpoint</li>
                <li><strong>m_48</strong> = the image should be 48% of the screen size in the medium breakpoint</li>
                <li><strong>n_98</strong> = the image should be 98% of the screen size in the narrow breakpoint</li>
            </ul>
            <p>
                PS. we have a max of 1024px on this site.
            </p>

            <!--            <h2>Testing for capabilities</h2>

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
                    <li>Detected device: <span
                        class="feature-value"><?php echo MyWurfl::get('brand_name') . ' ' . MyWurfl::get('model_name') ?></span>
                    </li>
                    <li>Detected viewport size: <span
                        class="feature-value"><?php echo MyWurfl::get('max_image_width') . 'x' . MyWurfl::get('max_image_height') ?></span>
                    </li>
                    <li>Detected resolution width: <span
                        class="feature-value"><?php echo MyWurfl::get('resolution_width') . 'x' . MyWurfl::get('resolution_height') ?></span>
                    </li>
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
            </div>-->
            <div class="cf"></div>

            <h2>Test Images</h2>

            <div class="cf"></div>


            <div class="g1 grid">
                <h3>First test</h3>


            </div>
            <div class="g3 grid">
                <div class="image">
                    <img id="img3"
                         src="http://whateverweb.com/img/vpw_1024/bp_w/pc/w_31/m_48/n_98/http://farm3.staticflickr.com/2702/4346062272_8b4a4a18cc_b.jpg"/>
                </div>
            </div>
            <div class="g4 grid">


                <div class="text">
                    The area containing the image in this row is defined to be:<br/>
                    <ul>
                        <li>31% in the wide breakpoint</li>
                        <li>48% in medium breakpoint</li>
                        <li>98% in the narrow breakpoint</li>
                    </ul>
                    <h4>Actual values</h4>

                    <div id="img3-debug"></div>
                </div>
            </div>
            <div class="cf"></div>


            <div class="g1 grid">
                <h3>Second test</h3>
            </div>

            <div class="g2 grid">

                <div class="image">
                    <img id="img2"
                         src="http://whateverweb.com/img/vpw_1024/bp_w/pc/w_48/m_48/n_98/http://farm3.staticflickr.com/2702/4346062272_8b4a4a18cc_b.jpg"/>
                </div>
            </div>
            <div class="g2 grid">
                <div class="text">
                    The area containing the image in this row is defined to be:<br/>
                    <ul>
                        <li>48% in the wide breakpoint</li>
                        <li>48% in medium breakpoint</li>
                        <li>98% in the narrow breakpoint</li>
                    </ul>
                    <h4>Actual values</h4>

                    <div id="img2-debug"></div>
                </div>
            </div>

            <div class="cf"></div>
            <div class="g1 grid">
                <h3>Third test</h3>

                <div class="image">
                    <img id="img1"
                         src="http://whateverweb.com/img/vpw_1024/bp_w/pc/w_98/m_98/n_98/http://farm3.staticflickr.com/2702/4346062272_8b4a4a18cc_b.jpg"/>
                </div>
            </div>
            <div class="g1 grid">
                <div class="text">
                    The area containing the image in this row is defined to be:<br/>
                    <ul>
                        <li>98% in the wide breakpoint</li>
                        <li>98% in medium breakpoint</li>
                        <li>98% in the narrow breakpoint</li>
                    </ul>
                    <h4>Actual values</h4>

                    <div id="img1-debug"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="cf"></div>
    <small>Photos <a href="http://www.flickr.com/photos/glenn-in-japan/4346062272/sizes/l/in/photostream/">http://www.flickr.com/photos/glenn-in-japan/4346062272/sizes/l/in/photostream//</a>
    </small>

</div>
<footer class="">
    <small>2011 <span class="license">Created by <a href="http://about.me/ama">Anders Magnus Andersen</a> - <a
        href="http://twitter.com/andmag">@andmag</a><br/>
            With help from: <a href="http://www.modernizr.com/">Modernizr</a> &amp; WURFL from <a
            href="http://www.scientiamobile.com/">ScientiaMobile</a> &amp; <a href="http://jquery.com/">jQuery</a> &amp; <a
            href="http://html5boilerplate.com/">HTML5 Boilerplate</a> &amp; <a href="http://typekit.com">Typekit</a> &amp; Dynamix-carousel plugin by <a
            href="https://github.com/Wilto/Dynamic-Carousel">Mat Marquis (@wilto)</a>
    </small>
</footer>
<!--! end of #container -->
</body>
<?php include 'blocks/html-foot.php' ?>