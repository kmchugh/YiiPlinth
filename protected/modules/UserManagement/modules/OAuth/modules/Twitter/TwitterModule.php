<?php

class TwitterModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'Twitter.models.*',
			'Twitter.components.*',
		));

		$this->getParentModule()->onRetrieveOAuthProviderLinks = array($this, 'getOAuthLink');
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

	/**
	 * Populates the OAuthLinks array with the authorisation link for this Provider
	 * @param  CEvent $toEvent the event that occured, contains an OAuthLinks parameter
	 */
	public function getOAuthLink($toEvent)
	{
		$toEvent->params['OAuthLinks']['Twitter']='/UserManagement/OAuth/Twitter';
	}
}
