<?php

return array(
    'theme'=>'default',

    'preload'=>array('layoutMap'),

    'modules'=>array(
        // uncomment the following to enable the Gii tool
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>false,//'localpassword',
            'ipFilters'=>array('127.0.0.1','::1'),
        ),
    ),
    // application components
    'components'=>array(
        'image'=>array(
            'class'=>'YIIPlinth.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            'params'=>array('directory'=>'/opt/local/bin'),
        ),
    ),
);

?>