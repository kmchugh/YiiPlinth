<?php
/**
* This is the default configuration for the UserManagement module
*/
return array(
	'components'=>array(
		'urlManager'=>array(
			'rules'=>array(
				array('WS/list', 'pattern'=>'(WS|ws)/<model:\w+>', 'verb'=>'GET'),
				array('WS/view', 'pattern'=>'WS/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
				array('WS/view', 'pattern'=>'WS/<model:\w+>/<guid:\w+>', 'verb'=>'GET'),
				array('WS/update', 'pattern'=>'WS/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
				array('WS/update', 'pattern'=>'WS/<model:\w+>/<guid:\w+>', 'verb'=>'PUT'),
				array('WS/create', 'pattern'=>'WS/<model:\w+>/', 'verb'=>'POST'),
				array('WS/delete', 'pattern'=>'WS/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
				array('WS/delete', 'pattern'=>'WS/<model:\w+>/<guid:\w+>', 'verb'=>'DELETE'),
			),
		),
	),
);
?>