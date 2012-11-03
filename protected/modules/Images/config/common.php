<?php
/**
* This is the default configuration for the UserManagement module
*/
return array(
    'components'=>array(
        'urlManager'=>array(
            'rules'=>array(
                '/images/<path:.+>'=>'Images/default',
                ),
            ),
    ),
);