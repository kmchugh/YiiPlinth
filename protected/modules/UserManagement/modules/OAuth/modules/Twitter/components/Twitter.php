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

	protected function urlencode($tcInput) 
	{
		return (is_scalar($tcInput)) ?
			str_replace('+',' ',str_replace('%7E', '~', rawurlencode($tcInput))) :
			'';
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


}

?>