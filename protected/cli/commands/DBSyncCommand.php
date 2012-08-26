<?php

Yii::import('system.cli.commands.MigrateCommand');
class DBSyncCommand extends MigrateCommand
{
	/**
	 * @var string the directory that stores the migrations. This must be specified
	 * in terms of a path alias, and the corresponding directory must exist.
	 * Defaults to 'application.migrations' (meaning 'protected/migrations').
	 */
	public $migrationPath='application.migrations';
	/**
	 * @var string the name of the table for keeping applied migration information.
	 * This table will be automatically created if not exists. Defaults to 'tbl_migration'.
	 * The table structure is: (version varchar(255) primary key, apply_time integer)
	 */
	public $migrationTable='database_version';
	/**
	 * @var string the application component ID that specifies the database connection for
	 * storing migration information. Defaults to 'db'.
	 */
	public $connectionID='db';
	/**
	 * @var string the path of the template file for generating new migrations. This
	 * must be specified in terms of a path alias (e.g. application.migrations.template).
	 * If not set, an internal template will be used.
	 */
	public $templateFile;
	/**
	 * @var string the default command action. It defaults to 'up'.
	 */
	public $defaultAction='up';
	/**
	 * @var boolean whether to execute the migration in an interactive mode. Defaults to true.
	 * Set this to false when performing migration in a cron job or background process.
	 */
	public $interactive=true;

	private $m_aParsedPaths;

	/**
	 * Returns an array of migration paths
	 * @param  string or array the path of the migrations
	 * @return array         fully qualified array of migration paths
	 */
	private function getMigrationPaths($taPath)
	{
		if ($this->m_aParsedPaths == NULL)
		{
			$taPath = !is_array($taPath) ? array($taPath) : $taPath;
			self::extractModuleMigration(Yii::app()->modules, Yii::getPathOfAlias('YIIPlinth'), $taPath);

			$this->m_aParsedPaths = array();
			foreach ($taPath as $lcPath) 
			{
				$this->m_aParsedPaths[] = Utilities::ISNULLOREMPTY(Yii::getPathOfAlias($lcPath), $lcPath);
			}
		}
		return $this->m_aParsedPaths;
	}

	/**
	* Adds the module migrations to the migration path
	* @param $taModule the module configuration
	* @param $taConfig the configuration file list
	**/
	private function extractModuleMigration($taModule, $tcPath, &$taConfigList)
	{
		if (is_array($taModule))
		{
			foreach ($taModule as $lcName => $laConfig) 
			{
				$lcName = is_numeric($lcName) ? $laConfig : $lcName;
				$taConfigList[] = $tcPath.'/modules/'.$lcName."/migrations";
				if (isset($laConfig['modules']))
				{
					self::extractModuleMigration($laConfig['modules'], $tcPath."/modules/$lcName", $taConfigList);
				}
			}
		}
	}




	public function beforeAction($tcAction,$taParams)
	{
		self::getMigrationPaths($this->migrationPath);
		echo "\n-- MIGRATION PATHS --\n\n";
		foreach (self::getMigrationPaths($this->migrationPath) as $lcPath)
		{
			if($lcPath===false || !is_dir($lcPath))
			{
				echo '(not created) - ';
			}
			echo "$lcPath\n";
		}
		echo "\nPlinth Migration Extension v1.0 (based on Yii v{".Yii::getVersion()."})\n\n";
		return true;
	}

	protected function instantiateMigration($tcClass)
	{
		$lcFile = NULL;
		foreach ($this->m_aParsedPaths as $lcPath) 
		{
			echo $lcPath.DIRECTORY_SEPARATOR."$tcClass.php \n";
			if (file_exists($lcPath.DIRECTORY_SEPARATOR."$tcClass.php"))
			{
				$lcFile = $lcPath.DIRECTORY_SEPARATOR."$tcClass.php";
				require_once($lcFile);
				$loMigration=new $tcClass;
				$loMigration->setDbConnection($this->getDbConnection());
				return $loMigration;
			}
		}
		return NULL;
	}

	public function actionCreate($args)
	{
		if(isset($args[0]))
		{
			$lcName=$args[0];
		}
		else
		{
			$this->usageError('Please provide the name of the new migration.');
		}

		if(!preg_match('/^\w+$/',$lcName))
			die("Error: The name of the migration must contain letters, digits and/or underscore characters only.\n");

		$lcName='m'.gmdate('ymd_His').'_'.$lcName;
		$lcContent=strtr($this->getTemplate(), array('{ClassName}'=>$lcName));
		$lcFile=Yii::getPathOfAlias('YIIPlinth.migrations').DIRECTORY_SEPARATOR.$lcName.'.php';

		if($this->confirm("Create new migration '$lcFile'?"))
		{
			file_put_contents($lcFile, $lcContent);
			echo "New migration created successfully.\n";
		}
	}

	protected function getNewMigrations()
	{
		$laApplied=array();
		foreach($this->getMigrationHistory(-1) as $lnVersion=>$lnTime)
		{
			$laApplied[substr($lnVersion,1,13)]=true;
		}

		$laMigrations=array();
		foreach ($this->m_aParsedPaths as $lcPath) 
		{
			if (is_dir($lcPath))
			{
				$lnHandle=opendir($lcPath);
				while(($loFile=readdir($lnHandle))!==false)
				{
					if($loFile==='.' || $loFile==='..')
					{
						continue;
					}

					$lcMigrationFile=$lcPath.DIRECTORY_SEPARATOR.$loFile;
					if(preg_match('/^(m(\d{6}_\d{6})_.*?)\.php$/',$loFile,$laMatches) && is_file($lcMigrationFile) && !isset($laApplied[$laMatches[2]]))
					{
						$laMigrations[]=$laMatches[1];
					}
				}
				closedir($lnHandle);
			}
		}
		sort($laMigrations);
		return $laMigrations;
	}

	public function getHelp()
	{
		return <<<EOD
USAGE
  yiic dbsync [action] [parameter]

DESCRIPTION
  This command provides support for database migrations. The optional
  'action' parameter specifies which specific migration task to perform.
  It can take these values: up, down, to, create, history, new, mark.
  If the 'action' parameter is not given, it defaults to 'up'.
  Each action takes different parameters. Their usage can be found in
  the following examples.

EXAMPLES
 * yiic dbsync
   Applies ALL new migrations. This is equivalent to 'yiic dbsync up'.

 * yiic dbsync create create_user_table
   Creates a new migration named 'create_user_table'.

 * yiic dbsync up 3
   Applies the next 3 new migrations.

 * yiic dbsync down
   Reverts the last applied migration.

 * yiic dbsync down 3
   Reverts the last 3 applied migrations.

 * yiic dbsync to 101129_185401
   Migrates up or down to version 101129_185401.

 * yiic dbsync mark 101129_185401
   Modifies the migration history up or down to version 101129_185401.
   No actual migration will be performed.

 * yiic dbsync history
   Shows all previously applied migration information.

 * yiic dbsync history 10
   Shows the last 10 applied migrations.

 * yiic dbsync new
   Shows all new migrations.

 * yiic dbsync new 10
   Shows the next 10 migrations that have not been applied.

EOD;
	}

	protected function getTemplate()
	{
		if($this->templateFile!==null)
			return file_get_contents(Yii::getPathOfAlias($this->templateFile).'.php');
		else
			return <<<EOD
<?php

class {ClassName} extends CDbMigration
{
	public function up()
	{
	}

	public function down()
	{
		echo "{ClassName} does not support migration down.\\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
EOD;
	}
}
