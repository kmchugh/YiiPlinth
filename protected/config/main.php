<?php
/**
* This is the default configuration for a YiiPlinth application.
*/
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'YiiPlinth',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.helpers.*',
		'application.components.*',
	),

	'modules'=>array(
		// Note gii should not be available in the runtime environment
	),

	// application components
	'components'=>array(
		'db'=>array(
			'connectionString'=>'mysql:host=127.0.0.1;dbname=YiiPlinth',
			'emulatePrepare'=>true,
			'charset'=>'utf8',
			'tablePrefix'=> '',
			),
		'session'=>array(
			'sessionName'=>'PHPSESSID',
			'class'=>'YIIPlinth.extensions.Session.PlinthDBSession',
			'connectionID'=>'db',
			'sessionTableName'=>'Session',
			),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
	),
);