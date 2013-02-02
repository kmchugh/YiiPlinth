<?php
/**
* This is the default configuration for the UserManagement module
*/
return array(
	'components'=>array(
		'user'=>array(
			'class'=>'YIIPlinth.modules.UserManagement.components.PlinthWebUser',
			'allowAutoLogin'=>true,
			'loginUrl'=>'/login',
			),
		'urlManager'=>array(
			'rules'=>array(
				'/login'=>'UserManagement/access/Login',
				'/site/login'=>'UserManagement/access/Login',

				'/logout'=>'UserManagement/access/Logout',
				'/site/logout'=>'UserManagement/access/Logout',

				'/passwordReset'=>'UserManagement/access/PasswordReset',
				'/site/passwordReset'=>'UserManagement/access/PasswordReset',

				'/register'=>'UserManagement/access/Register',
				'/site/register'=>'UserManagement/access/Register',

				'/retrieveEmail'=>'UserManagement/access/RetrieveEmail',
				'/site/retrieveEmail'=>'UserManagement/access/RetrieveEmail',

                '/changePassword'=>'UserManagement/access/ChangePassword',
                '/site/changePassword'=>'UserManagement/access/ChangePassword',
                '/changePassword/token/<token:.*>'=>'UserManagement/access/ChangePassword/token/<token>',
				),
			),
	),
);