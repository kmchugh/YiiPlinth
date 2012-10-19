<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$loOauth = new Twitter();
		$loToken = $loOauth->getRequestToken(Utilities::getURL().'/default/Callback');
	}

	public function actionCallback()
	{
		if (isset($_REQUEST['oauth_token']))
		{
			// Retrieve the access token
			$loOauth = new Twitter();
			$loAuthUser = $loOauth->updateAccessToken($_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);

			if (!is_null($loAuthUser))
			{
				// Determine if this is a new user or an old user
				if (!isset($loAuthUser->UserID))
				{
					// retrieve new user email address
					echo "New User";
				}
				// User has just "authenticated" with twitter
				if ($this->loginUser($loAuthUser->user))
				{
					// TODO: Redirect to where we came from
					Yii::app()->getController()->redirect('/');
				}
			}
			else
			{
				// No OAuth User, redirect to login
				Yii::app()->getController()->redirect('/login');
			}
		}
	}

	private function loginUser($toUser)
	{
		if (!is_null($toUser))
		{
			$loUserIdentity=new PlinthUserIdentity($toUser->Email,$toUser->Password);
			Yii::app()->user->login($loUserIdentity,3600*24*30);
			return true;
		}
		return false;
	}
}