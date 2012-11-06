<?php
abstract class OAuthBaseModule extends CWebModule
{
	public $apiKey = NULL;
	public $apiSecret = NULL;
	public $callbackURI = NULL;
	public $redirectURI = NULL;
	public $providerName = NULL;

	/**
	 * Initialises the module, if overriding parent::init should be called
	 */
	public function init()
	{
		$this->afterInit();
	}

	/**
	 * Occurs after the initialisation is completed.
	 */
	protected function afterInit()
	{
		$this->getParentModule()->onRetrieveOAuthProviderLinks = array($this, 'getOAuthLink');
	}

	/**
	 * Occurs before any Controller action takes place
	 * @param  CController $controller The controller that the action will be executed on
	 * @param  string $action     The action that is occurring
	 * @return boolean  return true for success, false for failure
	 */
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

	/**
	 * Populates the OAuthLinks array with the authorisation link for this Provider
	 * @param  CEvent $toEvent the event that occured, contains an OAuthLinks parameter
	 */
	public function getOAuthLink($toEvent)
	{
		$toEvent->params['OAuthLinks'][$this->providerName]='/UserManagement/OAuth/Facebook';
	}
}
?>