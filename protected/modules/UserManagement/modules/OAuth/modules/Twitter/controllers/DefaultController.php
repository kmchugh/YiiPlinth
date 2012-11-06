<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		Utilities::setCallbackURL(NULL);
		Utilities::updateCallbackURL();
		$loOauth = new Twitter();
		if ($loOauth->hasAPIKey())
		{
			$loToken = $loOauth->getRequestToken(Utilities::getURL().'/default/Callback');
		}
		else
		{
			echo 'API Keys have not been set for '.$loOauth->getProviderName();
		}
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
					$_SESSION['OAuthUser'] = $loAuthUser->OAuthUserID;
					Yii::app()->getController()->redirect('/retrieveEmail');
				}
				// User has just "authenticated" with twitter
				else if ($this->loginUser($loAuthUser->user))
				{
					Yii::app()->getController()->redirect(preg_match('/.+?\/login/', Utilities::getCallbackURL()) ? '/' : Utilities::getCallbackURL());
				}
			}
			else
			{
				// No OAuth User, redirect to login
				Yii::app()->getController()->redirect('/login');
			}
		}
	}
}