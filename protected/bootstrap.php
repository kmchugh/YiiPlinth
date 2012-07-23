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

			// Finally include the custom config for the user
			$laConfig[] = $_ENV['HOME']."/".$_SERVER["SERVER_NAME"]."/config/customConfig.php";

			// Include the YII Framework
			require_once($lcYII);

			// Setup an alias for YIIPlinth
			YiiBase::setPathOfAlias('YIIPlinth', dirname(__FILE__));

			// Merge the arrays for the configuration
			$loConfiguration = Utilities::mergeIncludedArray($laConfig);

			// And off we go...
			Yii::createWebApplication($loConfiguration)->run();
		}
	}
	return new YIIPlinth();
?>