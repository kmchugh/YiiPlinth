<!DOCTYPE html>
<!-- DERIVED FROM http://html5boilerplate.com/ -->
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head profile="http://www.w3.org/2005/10/profile">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
        <meta http-equiv="imagetoolbar" content="no"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="language" content="en"/>

        <title><?php echo Yii::app()->name .' - '.CHtml::decode($this->pageTitle); ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link href="/images/icons/logo_57x57.png" rel="apple-touch-icon" type="image/png">
        <link href="/images/icons/logo_32x32.png" rel="icon" type="image/png">
        <link href="/images/icons/logo_32x32.png" rel="shortcut icon" type="image/png">
        <link href="/sitemap.xml" rel="sitemap" title="Sitemap" type="application/xml">

        <?php /*
            This is a php comment so it does not show in the resulting page.

            Adding scripts or css files should be completed using the commands : 
            Yii::app()->clientScript->registerCssFile('PrimaryPage.less');
            Yii::app()->clientScript->registerScriptFile('/javascript/libs/dromos/dromos.bootstrap.js', CClientScript::POS_HEAD);
         */ ?>

</head>

<body>
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->

    <?php
     echo (preg_match('/.+?<\?php/i', $content) > 0) ?
            eval(' ?>'.$content.'<?php ') : $content;
    ?>

    <script type="text/javascript" charset="UTF-8" async="true">
        document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/,'js');
    </script>
</body>

</html>