<?php

	require_once( dirname(__FILE__) . '/components/Utilities.php');

	class YIIPlinth
	{
		/**
		* Boot the app with the specified options.
		* yii = the location of the yii framework
		* root = the entry point to the index.php for the site.
		**/
		public function boot($taOptions)
		{
			if (!isset($taOptions['yii']) || !isset($taOptions['root']))
			{
				echo "The root or the yii directory have not been set";
				exit;
			}
			$lcYII=$taOptions['yii'];

			// Include the application configuration files
			$laConfig[]=$taOptions['root'].'/protected/config/main.php';
			$laConfig[]=dirname(__FILE__).'/config/main.php';

			// If we are in development mode, include the development config
			if (Utilities::isDevelopment())
			{
				$laConfig[]=$taOptions['root'].'/protected/config/development.php';

				defined('YII_DEBUG') or define('YII_DEBUG',true);
				defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
				error_reporting(E_ALL);
			}

			// Include the YII Framework
			require_once($lcYII);

			// Setup an alias for YIIPlinth
			YiiBase::setPathOfAlias('YIIPlinth', dirname(__FILE__));

			// Merge the arrays for the configuration
			$loConfiguration = Utilities::mergeIncludedArray($laConfig);
			// Merge any installed module config files
			if (isset($loConfiguration['modules']))
			{
				self::extractModuleConfig($loConfiguration['modules'], YiiBase::getPathOfAlias('YIIPlinth'), $laConfig);
			}

			// Finally include the custom config for the user, this is included last so that it is possible to override any settings
			$laConfig[] = $_ENV['HOME']."/".$_SERVER["SERVER_NAME"]."/config/customConfig.php";
			$loConfiguration = Utilities::mergeIncludedArray($laConfig);

			// And off we go...
			Yii::createWebApplication($loConfiguration)->run();
		}

		/**
		* Adds the config file for the specified module if it exists, otherwise a no op
		* @param $taModule the module configuration
		* @param $taConfig the configuration file list
		**/
		private function extractModuleConfig($taModule, $tcPath, &$taConfigList)
		{
			if (is_array($taModule))
			{
				foreach ($taModule as $lcName => $laConfig) 
				{
					$lcName = is_array($laConfig) ? $lcName : $laConfig;
					// Extract this module config, for now this only supports YIIPlinth modules
					$lcConfigFile = $tcPath.'/modules/'.$lcName.'/config/main.php';

					if (file_exists($lcConfigFile))
					{
						$taConfigList[] = $lcConfigFile;
					}

					// Extract any child modules
					if (isset($laConfig['modules']))
					{
						self::extractModuleConfig($laConfig['modules'], $tcPath."/modules/$lcName", $taConfigList);
					}
				}
			}
		}
	}
	return new YIIPlinth();
?>