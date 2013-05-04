<?php
/**
* This is the default configuration for a YiiPlinth application.
*/
return array(
    'theme'=>'default',
    // autoloading model and component classes
    'import'=>array(
        'YIIPlinth.extensions.crontab.*',
    ),
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