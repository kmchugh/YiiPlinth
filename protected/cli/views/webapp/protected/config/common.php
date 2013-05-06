<?php

/**
 * Includes everything required for console, web, and testing apps
 */

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'APPLICATION_NAME',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.widgets.*',
    ),

    'modules'=>array(
        'WS'=>array(
            'class'=>'YIIPlinth.modules.WebService.WebServiceModule',
            'configuration'=>dirname(__FILE__).'/WSConfig'
        ),
        // TODO: Need to find a better way to make this case insensitive
        'ws'=>array(
            'class'=>'YIIPlinth.modules.WebService.WebServiceModule',
            'configuration'=>dirname(__FILE__).'/WSConfig'
        ),
    ),

    // application components
    'components'=>array(
        'db'=>array(
            'connectionString' => 'CONNECTION_STRING',
            'username' => 'DB_USER',
            'password' => 'DB_PASSWORD',
        ),
        'cache'=>array(
            'class'=>'CDbCache',
            'connectionID'=>'db',
        ),
        'errorHandler'=>array(
            'errorAction'=>'error/error',
        ),
        'user'=>array(
            'defaultProfileImageURI'=>'/images/profiles/defaultProfile-215.png',
        ),
        'layoutMap'=>array(
            'class'=>'YIIPlinth.components.LayoutMapManager',
            'map'=>array(
                // Default layout,theme, and style
                '/'=>array(
                    'layout'=>'//layouts/default',
                    'theme'=>'default',
                    'style'=>'/default.less',
                ),
                '/error/'=>array(
                    'layout'=>'//layouts/error',
                    'style'=>'/errorPage.less',
                ),
                '/site/page'=>array('style'=>'/templateView.less'),
                '/UserManagement/'=>array(
                    'layout'=>'//layouts/formPage',
                    'style'=>'/empty.less',),
            ),
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'APPLICATION_NAME.log',
                    'levels'=>'error, warning',
                ),
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'APPLICATION_NAME-Trace.log',
                    'levels'=>'trace',
                ),
            ),
        ),
    ),
    'params'=>array(
        'adminEmail'=>'no-reply@APPLICATION_NAME.com',
        'adminName'=>'APPLICATION_NAME',
    ),
);
?>