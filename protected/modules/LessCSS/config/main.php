<?php
/**
* This is the default configuration for the UserManagement module
*/
return array(
	'components'=>array(
		'urlManager'=>array(
			'rules'=>array(
				'<path:.+\/>?<file:\w+>\.less'=>'LessCSS/default',
				),
			),
	),
);