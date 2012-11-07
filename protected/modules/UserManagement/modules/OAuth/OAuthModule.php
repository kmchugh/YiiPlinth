<?php

class OAuthModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'OAuth.models.*',
			'OAuth.components.*',
			'OAuth.controllers.*',
		));

		// For each module that is installed, initialise it
		foreach ($this->getModules() as $lcName => $loModule)
		{
			$loModule = $this->getModule($lcName);
		}

		// Register for events we want to handle
		$this->getParentModule()->onPrepareRegistration = array($this, 'injectOAuthRegistration');
		$this->getParentModule()->onPrepareSignIn = array($this, 'injectOAuthSignIn');
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

	public function onRetrieveOAuthProviderLinks($toEvent)
	{
		$this->raiseEvent("onRetrieveOAuthProviderLinks", $toEvent);
	}

	/**
	 * Injects the OAuthProvider links into the registration form page.
	 * @param  CEvent $toEvent the event that occured, this will contain a OAuthLinks parameter with the list of OAuthLinks that are being handled
	 */
	public function injectOAuthRegistration($toEvent)
	{
		if (isset($toEvent->params['form']))
		{
			// Extract all of the social links
			$loEvent = new CEvent($this, array("OAuthLinks"=>array()));
			$this->onRetrieveOAuthProviderLinks($loEvent);

			$lcOutput = $toEvent->sender->widget('YIIPlinth.modules.UserManagement.modules.OAuth.widgets.oauthProviders.OAuthProviders', array('OAuthLinks'=>$loEvent->params['OAuthLinks']), true);
			$toEvent->params['form'] = preg_replace('/<form/', $lcOutput.'<form', $toEvent->params['form'], 1);
		}
	}

	public function injectOAuthSignIn($toEvent)
	{
		$this->injectOAuthRegistration($toEvent);
	}
}
