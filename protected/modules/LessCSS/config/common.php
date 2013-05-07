<?php
/**
* This is the default configuration for the LessCSS module
*/
return array(
    'components'=>array(
        'urlManager'=>array(
            'rules'=>array(
                '<path:.+\/>?<file:\w+>\.less'=>'LessCSS/default',
                ),
            ),
    ),
);