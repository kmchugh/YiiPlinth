<?php

return array(
    'name'=>'Console - Maintenance',
    // application components
    'components'=>array(
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'console-Maintenance.log',
                    'levels'=>'error, warning',
                ),
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'console-Maintenance-Trace.log',
                    'levels'=>'trace',
                ),
            ),
        ),
    ));