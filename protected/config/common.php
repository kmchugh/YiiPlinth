<?php
/**
* This is the default configuration for a YiiPlinth application.
*/
return array(
    'charset'=>'utf-8',
    'sourceLanguage'=>'00',     // Force translation lookups
    'language'=>'en',           // Default language is English

    // autoloading model and component classes
    'import'=>array(
        'YIIPlinth.models.*',
        'YIIPlinth.models.behaviours.*',
        'YIIPlinth.components.*',
        'YIIPlinth.controllers.*',
        'YIIPlinth.widgets.*',
        'YIIPlinth.helpers.*',
        'YIIPlinth.extensions.Session.*',
        'YIIPlinth.extensions.Mail.*',
    ),

    'onBeginRequest'=>array('RequestHooks', 'beginRequest'),

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
        'Contact'=>array(
                'class'=>'YIIPlinth.modules.Contact.ContactModule',
            ),
        'LessCSS'=>array(
            'class'=>'YIIPlinth.modules.LessCSS.LessCSSModule',
            ),
        'Images'=>array(
            'class'=>'YIIPlinth.modules.Images.ImagesModule',
            ),
        'Messaging',
        'MailChimp',
    ),

    // application components
    'components'=>array(
        'session'=>array(
            'sessionName'=>'PHPSESSID',
            'class'=>'YIIPlinth.extensions.Session.PlinthDBSession',
            'connectionID'=>'db',
            'timeout'=>86400,
            'sessionTableName'=>'Session'
            ),
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
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'rules'=>array(
                '/'=>'site/index',

                // Default action
                //'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                ),
            ),
    ),
    'params'=>array(
        'defaults'=>array(
            'profileImages'=>array(),
            ),
    ),
);