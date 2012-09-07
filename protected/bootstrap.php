<?php

	require_once(dirname(__FILE__) . '/components/Utilities.php');

	class YIIPlinth
	{
		/**
		* Boot the app with the specified options.
		* yii = the location of the yii framework
		* root = the entry point to the index.php for the site.
		**/
		public function boot($taOptions, $tcConfigType = NULL)
		{
			if (!isset($taOptions['yii']) || !isset($taOptions['root']))
			{
				echo "The root or the yii directory have not been set";
				exit;
			}

			$lcYII=$taOptions['yii'];
			$llConsole = basename(strtolower($lcYII), '.php') === 'yiic';

			$tcConfigType = is_null($tcConfigType) ?
				($llConsole ? 'console' :
					(Utilities::isDevelopment() ? 'development' : 'main'))
				  : $tcConfigType;

			// Plinth Config files
			$laConfig[]=dirname(__FILE__)."/config/common.php";
			$laConfig[]=dirname(__FILE__)."/config/$tcConfigType.php";

			// Application Config Files
			$laConfig[]=$taOptions['root']."/protected/config/common.php";
			$laConfig[]=$taOptions['root']."/protected/config/$tcConfigType.php";

			if (isset($_ENV['HOME']))
			{
				// Custom Installation Config
				$laConfig[] = $_ENV['HOME']."/".$_SERVER["SERVER_NAME"]."config/common.php";
				$laConfig[] = $_ENV['HOME']."/".$_SERVER["SERVER_NAME"]."config/$tcConfigType.php";
			}

			// Build the config array
			$loConfig = Utilities::mergeIncludedArray($laConfig);

			// Extract the modules
			if (isset($loConfig['modules']))
			{
				$laModuleConfig = array();
				self::extractModuleConfig($loConfig['modules'], dirname(__FILE__), $laModuleConfig, $tcConfigType);
				self::extractModuleConfig($loConfig['modules'], $taOptions['root'], $laModuleConfig, $tcConfigType);

				// Merge the module overrides
				$loModuleConfig = Utilities::mergeIncludedArray($laModuleConfig);
				$loConfig = Utilities::override($loConfig, $loModuleConfig);
			}
			if ($llConsole)
			{
				self::startConsole($lcYII, $loConfig);
			}
			else
			{
				self::processRequest($lcYII, $loConfig);
			}
		}

		/**
		 * Starts up the console application including the Plinth framework paths
		 * @param  string $tcYII    the file being started usually yii or yiic
		 * @param  array $toConfig the configuration of the application
		 */
		private function startConsole($tcYII, $toConfig)
		{
			defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
			defined('YII_DEBUG') or define('YII_DEBUG',true);

			require_once(dirname($tcYII).'/yii.php');

			YiiBase::setPathOfAlias('YIIPlinth', dirname(__FILE__));

			if(isset($toConfig))
			{
				$loApp=Yii::createConsoleApplication($toConfig);
				$loApp->commandRunner->addCommands(YII_PATH.'/cli/commands');
				$loEnv=@getenv('YII_CONSOLE_COMMANDS');
				if(!empty($loEnv))
					$loApp->commandRunner->addCommands($loEnv);
			}
			else
			{
				$loApp=Yii::createConsoleApplication(array('basePath'=>dirname($tcYII).'/cli'));
			}
			$loApp->run();
		}

		/**
		 * Processes the request from the client, starts the web application
		 * @param  string $tcYII  the application being started usually either yii or yiic
		 * @param  array $toConfig the configuration of the web application
		 */
		private function processRequest($tcYII, $toConfig)
		{
			// Include the YII Framework
			require_once($tcYII);

			// Setup an alias for YIIPlinth
			YiiBase::setPathOfAlias('YIIPlinth', dirname(__FILE__));

			// And off we go...
			Yii::createWebApplication($toConfig)->run();
		}

		/**
		* Adds the config file for the specified module if it exists, otherwise a no op
		* @param $taModule the module configuration
		* @param $taConfig the configuration file list
		**/
		private function extractModuleConfig($taModule, $tcPath, &$taConfigList, $tcConfigType)
		{
			if (is_array($taModule))
			{
				foreach ($taModule as $lcName => $laConfig) 
				{
					$lcName = is_array($laConfig) ? $lcName : $laConfig;

					// Extract this module config, for now this only supports YIIPlinth modules
					$lcConfigFile = $tcPath.'/modules/'.$lcName."/config/common.php";
					if (file_exists($lcConfigFile))
					{
						$taConfigList[] = $lcConfigFile;
					}
					$lcConfigFile = $tcPath.'/modules/'.$lcName."/config/$tcConfigType.php";
					if (file_exists($lcConfigFile))
					{
						$taConfigList[] = $lcConfigFile;
					}

					// Extract any child modules
					if (isset($laConfig['modules']))
					{
						self::extractModuleConfig($laConfig['modules'], $tcPath."/modules/$lcName", $taConfigList, $tcConfigType);
					}
				}
			}
		}
	}
	return new YIIPlinth();
?>