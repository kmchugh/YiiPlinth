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
		$this->configuration = file_exists($this->configuration.'.php') ?
			require_once($this->configuration.'.php') :
			array();

		// Always map to the default controller
		$this->controllerMap = array(preg_replace('/^\/?'.$this->id.'\/?/', '', $_SERVER['REQUEST_URI']) => dirname(__FILE__).'/controllers/DefaultController');

		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		

		// import the module-level models and components
		$this->setImport(array(
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		echo "$action - $controller";
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
