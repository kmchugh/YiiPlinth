<?php

class TwitterModule extends OAuthBaseModule
{
	public $providerName = 'Twitter';

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'Twitter.models.*',
			'Twitter.components.*',
		));

		parent::init();
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
