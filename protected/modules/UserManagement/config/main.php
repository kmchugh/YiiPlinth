<?php
/**
* This is the default configuration for the UserManagement module
*/
return array(
	'components'=>array(
		'urlManager'=>array(
			'rules'=>array(

				'/login'=>'UserManagement/access/Login',
				'/logout'=>'UserManagement/access/Logout',
				'/passwordReset'=>'UserManagement/access/PasswordReset',
				
				),
			),
	),
);