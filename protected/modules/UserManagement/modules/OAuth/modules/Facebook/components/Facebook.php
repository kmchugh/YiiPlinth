<?php

class Facebook extends OAuth
{
	public $host='https://www.facebook.com/dialog/';

	public $endpointURLs = array(
		'authenticate'=>'/oauth/authenticate',
		'authorize'=>'/oauth/authorize',
		'access'=>'/oauth/access_token',
		'request'=>'/oauth',
		);

	public function getProviderName()
	{
		return 'Facebook';
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


}

?>