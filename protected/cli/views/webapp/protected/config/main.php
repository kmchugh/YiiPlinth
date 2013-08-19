<?php
return array(
    'theme'=>'default',

    'preload'=>array('googleAnalytics', 'layoutMap'),

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