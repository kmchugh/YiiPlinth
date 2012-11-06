<?php

class Twitter extends OAuth
{
	public $host='https://api.twitter.com';

	public $endpointURLs = array(
		'authenticate'=>'/oauth/authenticate',
		'authorize'=>'/oauth/authorize',
		'access'=>'/oauth/access_token',
		'request'=>'/oauth/request_token',
		);

	public function getProviderName()
	{
		return 'Twitter';
	}

	public function getUserInfo($toAuthUser)
	{
		$loResponse = $this->makeRequest('https://api.twitter.com/1/users/show.json', 'GET', array('screen_name'=>$toAuthUser->DisplayName,), $toAuthUser);

		if ($loResponse != null)
		{
			return json_decode($loResponse['response']);
		}
		return NULL;
	}

	public function postTweet($toAuthUser, $tcTweet)
	{
		$loResponse = $this->makeRequest('https://api.twitter.com/1.1/statuses/update.json', 'POST', array(
			'status'=>$tcTweet,
			'include_entities'=>'true'), $toAuthUser, true);

		if ($loResponse != null)
		{
			return json_decode($loResponse['response']);
		}
		return NULL;
	}

	protected function beginCreateUser($toOAuthUser, $toExtraInfo)
	{
		$_SESSION['OAuthUser'] = $loAuthUser->OAuthUserID;
		Yii::app()->getController()->redirect('/retrieveEmail');
	}

	protected function updateOAuthUserInfo($taParameters, $toOAuthUser)
	{
		$loOAuthUser->setAttributes(Array(
			'Token'=>$laParameters['oauth_token'],
			'Secret'=>$laParameters['oauth_token_secret'],
			'Expires'=>Utilities::getTimestamp() * (1000 * 60 * 60 * 24 * 356);
			'UID'=>$laParameters['user_id'],
			'DisplayName'=>$laParameters['screen_name'],)
			);
	}

	protected function getUserForAccessRequest($taParameters)
	{
		$return Utilities::ISNULL(OAuthUser::model()->findByAttributes(array(
					'UID'=>$laParameters['user_id'],
					'Provider'=>$this->getProviderName(),)), $loOAuthUser);
	}

	public function handleRequest($toRequest)
	{
		echo "HANDLING";
		exit();

		if (isset($_REQUEST['oauth_token']))
		{
			// Retrieve the access token
			$loOauth = new Facebook();
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

		/*
		exit();
		if (!is_null($loRequest))
		{
			$laParameters = array();
			parse_str($loRequest['response'], $laParameters);
			if ($laParameters['oauth_callback_confirmed']==='true')
			{
				$loOAuth->setAttributes(Array(
				'Provider'=>$this->getProviderName(),
				'Verified'=>false,
				'Token'=>$laParameters['oauth_token'],
				'Secret'=>$laParameters['oauth_token_secret'],
				));
				Yii::app()->getController()->redirect($this->getEndpoint('authenticate').'?oauth_token='.$loOAuth->Token);
			}
		}*/
	}


}

?>