<?php
/**
* This is the default configuration for the UserManagement module
*/
return array(
	'components'=>array(
		'urlManager'=>array(
			'rules'=>array(
				'/(site/)?login'=>'UserManagement/access/Login',
				'/(site/)?logout'=>'UserManagement/access/Logout',
				'/passwordReset'=>'UserManagement/access/PasswordReset',
				'/register'=>'UserManagement/access/Register',
				),
			),
	),
);