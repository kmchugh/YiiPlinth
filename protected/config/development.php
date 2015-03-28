<?php

defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
error_reporting(E_ALL);

/**
* This is the default configuration for a YiiPlinth application.
*/
return array(
	// preloading 'log' component
	'preload'=>array('log'),

	'theme'=>'classic',

    'onBeginRequest'=>array('RequestHooks', 'beginRequest'),
);

?>