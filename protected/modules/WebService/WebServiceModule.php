<?php
/**
 * The Web Service Module processess all the web services that are used within the application
 */

/**
 * HTTP Verbs:
 * Idempotent:
 * GET
 * HEAD
 * PUT
 * DELETE
 * OPTIONS
 * TRACE
 * CONNECT
 *
 * POST
 */
class WebServiceModule extends CWebModule
{
	public $configuration;

	public function init()
	{
		// import the module-level models and components
		$this->setImport(array(
			$this->id.'.controllers.*',
		));

		$this->configuration = file_exists($this->configuration.'.php') ?
			require_once($this->configuration.'.php') :
			array();

		// Always map to the default controller
		$this->controllerMap = array(preg_replace('/^\/'.$this->id.'\/([^(\/|\?)]+)\/?.*?$/', '$1', $_SERVER['REQUEST_URI'], 1) =>'DefaultController');

		// this method is called when the module is being created
		// you may place code here to customize the module or the application
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

	public function getModelList()
	{
		$laReturn = array();
		foreach ($this->configuration['models'] as $lcModel=>$loModel)
		{
			$laReturn[] = $lcModel;
		}
		return $laReturn;
	}

	public function getModelInfo($tcModelName)
	{
		if (!isset($this->configuration['models']))
		{
			return null;
		}
		foreach ($this->configuration['models'] as $lcModel=>$loModel)
		{
			if (strcasecmp($tcModelName, $lcModel) == 0)
			{
				if (!isset($loModel['class']))
				{
					$lcModel = isset($loModel['model']) ? $loModel['model'] : $lcModel;
					if (class_exists($lcModel))
					{
						$loModel['class'] = $lcModel;
					}
					else
					{
						echo "Class does not exist";
					}
				}
				return $loModel;
			}
		}
		return NULL;
	}
}
