<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head profile="http://www.w3.org/2005/10/profile">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>

    <link href="/images/logos/logo_57x57.png" rel="apple-touch-icon" type="image/png">
    <link href="/images/logos/logo_32x32.png" rel="icon" type="image/png">
    <link href="/images/logos/logo_32x32.png" rel="shortcut icon" type="image/png">
    <link href="/sitemap.xml" rel="sitemap" title="Sitemap" type="application/xml">

    <!-- Styles -->
   <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/core.less"/>
    <title><?php echo Yii::app()->name .' - '.CHtml::decode($this->pageTitle); ?></title>

    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/javascript/dromos/dromos.bootstrap.js" charset="UTF-8"> </script>

    <script type="text/javascript" charset="UTF-8" async="true">
        document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/,'js');
    </script>

        <!-- TODO: Need to update the version seed on load of dromos -->
        <!-- TODO: Make the Google Analytics module -->
        <!--<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/javascript/libs/dromos/dromos.bootstrap.js" charset="UTF-8"> </script>-->

        <script type="text/javascript">
        /*
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-31055791-1']);
            _gaq.push(['_trackPageview']);
            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
             */
        </script>
</head>

<body>
    <header>
        <a href="/" title="home"><img alt="logo" src="/images/logos/logo_96x96.png"/></a>
        <nav>
                <?php 
                    $this->renderPartial('//site/_headerMenu');
                 ?>
        </nav>
    </header>

    <?php
     echo (preg_match('/.+?<\?php/i', $content) > 0) ?
            eval(' ?>'.$content.'<?php ') : $content;
    ?>

    <footer>
        <section class="row columns twoColumn">
            <div>
                <a href="/" title="home"><img alt="logo" src="/images/logos/logo_white_96x96.png"/></a>
                <div>Email: info@icatalyst.co.uk</div>
                <div>Tel: +44 (0)1865 522 212</div>
                 <!-- TODO: Make a social media component -->
                <ul class="social">
                    <li class="twitter"><a href="http://twitter.com/kmchugh12" target="_blank" rel="nofollow" title="follow us on twitter">Twitter</a></li>
                    <li class="facebook"><a href="http://www.facebook.com/kmchugh12" target="_blank" rel="nofollow" title="friend us on facebook">Facebook</a></li>
                    <li class="linkedin"><a href="http://http://www.linkedin.com/profile/view?id=9264147" target="_blank" rel="nofollow" title="connect to us on linkedin">LinkedIn</a></li>
                    <li class="feeds"><a href="/" title="RSS feeds" href="/rss.xml/">RSS Feeds</a></li>
                    <li class="ytube"><a href="http://www.youtube.com/user/kmchughTube?feature=creators_cornier-%2F%2Fs.ytimg.com%2Fyt%2Fimg%2Fcreators_corner%2FYouTube%2Fyoutube_32x32.
                        png" title="subscribe on YouTube"><img src="//s.ytimg.com/yt/img/creators_corner/YouTube/youtube_32x32.png" alt="Subscribe to me on YouTube"/></a></li>
                </ul>
            </div><div>
                <div class="newsfeed">
                    <h1>News Feed</h1>
                    <ul>
                        <li><a href="/" title="Item title">Item Title - <span>01 Jan 2012</span></a></li>
                        <li><a href="/" title="Item title">Item Title - <span>01 Jan 2012</span></a></li>
                        <li><a href="/" title="Item title">Fairly long item title with a good few words - <span>01 Jan 2012</span></a></li>
                        <li><a href="/" title="Item title">A very long news feed item title which due to its length will end up wrapping muliple lines of text. - <span>01 Jan 2012</span></a></li>
                    </ul>
                </div>
            </div>

            <nav>
                <div class="links">
                    <?php 
                        $this->renderPartial('//site/_headerMenu');
                    ?>
                </div>
                <label class="copyright">Copyright &copy; <?php echo @date('Y'); ?> by ICatalyst Ltd.  All Rights Reserved.</label>
            </nav>
        </section>
    </footer>

        <!--[if lt IE 7 ]>
        <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
        <script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
    <![endif]-->
</body>

</html>