<?php
/**
* This is the default configuration for a YiiPlinth application.
*/
return array(
	'commandMap'=>array(
		'dbsync'=>array(
			'class'=>'YIIPlinth.cli.commands.DBSyncCommand',
			'migrationPath'=>array(
				'YIIPlinth.migrations',
				'application.migrations',
				),
			),
		),
);