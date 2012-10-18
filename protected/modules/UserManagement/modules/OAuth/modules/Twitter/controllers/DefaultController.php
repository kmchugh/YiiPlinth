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
				if (isset($loAuthUser->UserID))
				{
					echo "Old User";
				}
				else
				{
					echo "New User";
				}
			}
			else
			{
				// No OAuth User, redirect to login
				Yii::app()->getController()->redirect('/Login');
			}
		}
	}
}