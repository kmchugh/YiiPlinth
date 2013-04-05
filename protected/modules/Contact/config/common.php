<?php
/**
 * This is the default configuration for the UserManagement module
 */
return array(
    'components'=>array(
        'urlManager'=>array(
            'rules'=>array(
                'contact'=>array('/Contact/Contact', 'caseSensitive'=>false),
                'site/contact'=>array('/Contact/Contact', 'caseSensitive'=>false),
            ),
        ),
    ),
);