<?php
/**
* This is the default configuration for the UserManagement module
*/
return array(
	'components'=>array(
		'urlManager'=>array(
			'rules'=>array(
				// TODO: Make 'WS' configurable
				// Web Service Interface

				'/login'=>'UserManagement/access/Login',
				'/logout'=>'UserManagement/access/Logout',
//				array('WS/delete', 'pattern'=>'WS/<model:\w+>/<guid:\w+>', 'verb'=>'DELETE'),

				// Default action

//				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				),
			),
	),
);