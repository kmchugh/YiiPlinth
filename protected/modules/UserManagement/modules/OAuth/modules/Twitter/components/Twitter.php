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
}

?>