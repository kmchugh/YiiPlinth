<?php
/**
* This is the default configuration for a YiiPlinth application.
*/
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'YiiPlinth',
	'charset'=>'utf-8',
	'sourceLanguage'=>'00',		// Force translation lookups
	'language'=>'en',			// Default language is English

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'YIIPlinth.models.*',
		'YIIPlinth.components.*',
		'YIIPlinth.controllers.*',
		'YIIPlinth.extensions.Session.*',
	),

	'theme'=>'classic',

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
	),

	// application components
	'components'=>array(
		'db'=>array(
			'connectionString'=>'mysql:host=127.0.0.1;dbname=YiiPlinth',
			'emulatePrepare'=>true,
			'charset'=>'utf8',
			'driverMap' => array(
				'mysql'=> 'YiiPlinth.components.db.mysql.PlinthMySQLSchema',
				),
			'tablePrefix'=> '',
			),
		'session'=>array(
			'sessionName'=>'PHPSESSID',
			'class'=>'YIIPlinth.extensions.Session.PlinthDBSession',
			'connectionID'=>'db',
			'sessionTableName'=>'Session',
			'timeout'=>1440,
			),
	),

	'controllerMap'=>array(
		'WS'=>'YIIPlinth.controllers.WSController',
		),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
	),
);