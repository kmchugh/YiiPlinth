<?php
/**
* This is the default configuration for the UserManagement module
*/
return array(
    'components'=>array(
        'urlManager'=>array(
            'rules'=>array(
                '[b|B]log/'=>'Blog/Blog/',
                'Blog/<action:\w+>'=>'Blog/Blog/<action>',
                ),
            ),
    ),
);