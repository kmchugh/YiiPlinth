<?php

class FacebookModule extends OAuthBaseModule
{
	public $providerName = 'Facebook';
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'Facebook.models.*',
			'Facebook.components.*',
		));

		parent::init();
	}

	/**
	 * Populates the OAuthLinks array with the authorisation link for this Provider
	 * @param  CEvent $toEvent the event that occured, contains an OAuthLinks parameter
	 */
	public function getOAuthLink($toEvent)
	{
		$toEvent->params['OAuthLinks'][$this->providerName]='/UserManagement/OAuth/Facebook';
	}

	/*
	public function buildCallback()
	{
		return 'https://www.facebook.com/dialog/oauth?client_id'.
	}
	*/
}
