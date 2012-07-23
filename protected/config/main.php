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
		'YIIPlinth.models.*',
		'YIIPlinth.components.*',
		'YIIPlinth.extensions.Session.*',
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
			'timeout'=>1440,
			),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
	),
);