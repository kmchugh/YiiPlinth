<?php

class UserManagementModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'UserManagement.models.*',
			'UserManagement.components.*',
			'UserManagement.widgets.accessControl.*',
		));

		if ($this->hasModule('OAuth'))
		{
			// Initialise the OAuth module
			$this->getModule("OAuth");
		}
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
	 * Occurs when  a user is registered in the system
	 * @param  CEvent $toEvent the event that occured, this will contain a 'user' parameter which is the user that was registered
	 */
	public function onUserRegistered($toEvent)
	{
		$this->raiseEvent("onUserRegistered", $toEvent);
	}

	/**
	 * Occurs when  a user changes their password
	 * @param  CEvent $toEvent the event that occured, this will contain a 'user' parameter which is the user being acted upon
	 */
	public function onPasswordChanged($toEvent)
	{
		$this->raiseEvent("onPasswordChanged", $toEvent);
	}

	/**
	 * Occurs when  a user resets their password
	 * @param  CEvent $toEvent the event that occured, this will contain a 'user' parameter which is the user being acted upon
	 */
	public function onPasswordReset($toEvent)
	{
		$this->raiseEvent("onPasswordReset", $toEvent);
	}

	/**
	 * Occurs when  the user registration form is being rendered
	 * @param  CEvent $toEvent the event that occured, this will contain a 'user' parameter which is the user being acted upon
	 */
	public function onPrepareRegistration($toEvent)
	{
		$this->raiseEvent("onPrepareRegistration", $toEvent);
	}

	/**
	 * Occurs when  the user sign in form is being rendered
	 * @param  CEvent $toEvent the event that occured, this will contain a 'user' parameter which is the user being acted upon
	 */
	public function onPrepareSignIn($toEvent)
	{
		$this->raiseEvent("onPrepareSignIn", $toEvent);
	}
}
