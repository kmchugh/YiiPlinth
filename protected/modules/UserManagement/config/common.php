<?php
/**
* This is the default configuration for the UserManagement module
*/
return array(
	'components'=>array(
		'user'=>array(
			'class'=>'UserManagement.components.PlinthWebUser',
			'loginUrl'=>'/login',
			),
		'urlManager'=>array(
			'rules'=>array(
				'/(site/)?login'=>'UserManagement/access/Login',
				'/(site/)?logout'=>'UserManagement/access/Logout',
				'/(site/)?passwordReset'=>'UserManagement/access/PasswordReset',
				'/(site/)?register'=>'UserManagement/access/Register',
				),
			),
	),
);