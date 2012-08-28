<?php
/**
* This is the default configuration for a YiiPlinth application.
*/
return array(

    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'YiiPlinth',
    'charset'=>'utf-8',
    'sourceLanguage'=>'00',     // Force translation lookups
    'language'=>'en',           // Default language is English

    // autoloading model and component classes
    'import'=>array(
        'YIIPlinth.models.*',
        'YIIPlinth.components.*',
        'YIIPlinth.controllers.*',
        'YIIPlinth.extensions.Session.*',
        'YIIPlinth.extensions.Mail.*',
    ),

    'modules'=>array(
        // Note gii should not be available in the runtime environment
        'UserManagement'=>array(
            'class'=>'YIIPlinth.modules.UserManagement.UserManagementModule',
            'modules'=>array(
                'OAuth'=>array(
                    'modules'=>array(
                        'Twitter',
                        'Facebook',
                        ),
                    ),
                ),
            ),
        'LessCSS'=>array(
            'class'=>'YIIPlinth.modules.LessCSS.LessCSSModule',
            ),
        'Messaging',
        'MailChimp',
        'WS'=>array(
            'class'=>'YIIPlinth.modules.WebServices.WebServiceModule',
            ),
    ),

    // application components
    'components'=>array(
        'db'=>array(
            'connectionString'=>'mysql:host=127.0.0.1;dbname=YiiPlinth',
            'emulatePrepare'=>true,
            'charset'=>'utf8',
            'driverMap' => array(
                'mysql'=> 'YIIPlinth.components.db.mysql.PlinthMySQLSchema',
                ),
            'tablePrefix'=> '',
            ),
        'mail'=>array(
            'class'=>'YIIPlinth.extensions.Mail.YiiMail',
            'transportType' => 'smtp',
            'transportOptions' => array(
                'host'=>'smtp.gmail.com',
                'encryption'=>'ssl',
                'port'=>465,
                ),
            'viewPath'=>'mail',
            'logging'=>true,
            'dryRun' => false,
            ),
        'authManager'=>array(
            'class'=>'CDbAuthManager',
            'connectionID'=>'db',
            ),
        'session'=>array(
            'sessionName'=>'PHPSESSID',
            'class'=>'YIIPlinth.extensions.Session.PlinthDBSession',
            'connectionID'=>'db',
            'sessionTableName'=>'Session',
            'timeout'=>1440,
            ),
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'rules'=>array(
                // TODO: Make 'WS' configurable
                // Web Service Interface
                array('WS/default/list', 'pattern'=>'WS/<model:\w+>', 'verb'=>'GET'),
                array('WS/default/view', 'pattern'=>'WS/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
                array('WS/default/view', 'pattern'=>'WS/<model:\w+>/<guid:\w+>', 'verb'=>'GET'),
                array('WS/default/update', 'pattern'=>'WS/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
                array('WS/default/update', 'pattern'=>'WS/<model:\w+>/<guid:\w+>', 'verb'=>'PUT'),
                array('WS/default/create', 'pattern'=>'WS/<model:\w+>/', 'verb'=>'POST'),
                array('WS/default/delete', 'pattern'=>'WS/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
                array('WS/default/delete', 'pattern'=>'WS/<model:\w+>/<guid:\w+>', 'verb'=>'DELETE'),

                // Default action
                //'<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                'caseSensitive'=>false,
                ),
            ),
    ),

);