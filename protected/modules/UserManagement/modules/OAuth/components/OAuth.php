<?php

//require_once('OAuth.php');

class OAuth
{
	public $userAgent;
	public $timeout=30;
	public $sslVerifyPeer = FALSE;
	public $version="1.0";

	private $m_oHTTPHeader;
	private $m_oSignatureMethod = NULL;

	public $endpointURLs = array(
		'authenticate'=>'oauth/authenticate',
		'authorize'=>'oauth/authorize',
		'access'=>'oauth/access_token',
		'request'=>'oauth/request_token',
		);

	public function __construct()
	{
		$this->userAgent=Yii::app()->name.' HTTP AGENT 1.1';
	}

	/**
	 * Gets the initial request token 
	 * @param  String $toCallbackURL the url to call back to when the token is issued and redirect is needed
	 * @return Array an associative array containing the keys oauth_token and oauth_token_secret
	 */
	public function getRequestToken($toCallbackURL = NULL)
	{
		$loParameters = array();
		if (!is_null($toCallbackURL))
		{
			$loParameters['oauth_callback']=$toCallbackURL;
		}
		$loRequest = $this->makeRequest($this->getEndpoint('request'), 'POST', $loParameters);
		if (!is_null($loRequest))
		{
			$laParameters = array();
			parse_str($loRequest['response'], $laParameters);
			if ($laParameters['oauth_callback_confirmed']==='true')
			{
				$loOAuth = new OAuthUser();
				$loOAuth->setAttributes(Array(
					'Provider'=>$this->getProviderName(),
					'Token'=>$laParameters['oauth_token'],
					'Secret'=>$laParameters['oauth_token_secret'],
					));
				$loOAuth->save();
				Yii::app()->getController()->redirect($this->getEndpoint('authenticate').'?oauth_token='.$loOAuth->Token);
			}
		}
	}

	/**
	 * Gets the user with the specified token
	 * @param  string $tcToken The token that we are using to get the user
	 * @return OAuthUser object, or null if none was found
	 */
	private function getUserForToken($tcToken)
	{
		return OAuthUser::model()->findByAttributes(array(
			'Token' => $tcToken,
			'Provider'=>$this->getProviderName()));
	}

	/**
	 * This method updates the OAuth record with the new token and token secret
	 * @param  string $tcToken   The request token returned from the Provider
	 * @param  string $tcVerifier The verifier returned from the Provider
	 * @return string The OAuthUser record for this token
	 */
	public function updateAccessToken($tcToken, $tcVerifier)
	{
		$loOAuthUser = $this->getUserForToken($tcToken);

		if (!is_null($loOAuthUser))
		{
			$loParameters = array();
			$loOAuthUser->Token = $tcToken;
			$loParameters['oauth_verifier']=$tcVerifier;
			$loRequest = $this->makeRequest($this->getEndpoint('access'), 'POST', $loParameters, $loOAuthUser);

			if (!is_null($loRequest))
			{
				$laParameters = array();
				parse_str($loRequest['response'], $laParameters);

				// Find if this user already exists
				$loUser = Utilities::ISNULL(OAuthUser::model()->findByAttributes(array(
						'UID'=>$laParameters['user_id'],
						'Provider'=>$this->getProviderName(),)), $loOAuthUser);
				if ($loUser !== $loOAuthUser)
				{
					$loOAuthUser->delete();
					$loOAuthUser = $loUser;
				}
	 			$loOAuthUser->setAttributes(Array(
					'Token'=>$laParameters['oauth_token'],
					'Secret'=>$laParameters['oauth_token_secret'],
					'UID'=>$laParameters['user_id'],
					'DisplayName'=>$laParameters['screen_name'],)
					);
	 			$loOAuthUser->save();
	 		}
	 		else
	 		{
	 			$loOAuthUser = NULL;
	 		}
		}
		return $loOAuthUser;
	}

	/**
	 * Ensures the correct endpoint url will be used, this will return the host + endpoint if the endpoint does not
	 * include http or https otherwise it will just return the endpoint
	 * @param  String $tcType The end point that is being retrieved
	 * @return String the full enpoint url
	 */
	private function getEndpoint($tcType)
	{
		$lcReturn = $this->endpointURLs[$tcType];
		if (!preg_match('/^https?:\/\/.+/i', $lcReturn))
		{
			$lcReturn=$this->host.$lcReturn;
		}
		return $lcReturn;
	}

	/**
	 * Makes the request to the OAuth client, returns the response if the call was successful
	 * @param  string $tcURL        The URL of the OAuth client endpoint
	 * @param  string $tcMethod     The http method to use to make the call.  Usually GET or POST
	 * @param  array  $toParameters The list of parameters
	 * @return array a response object
	 */
	protected function makeRequest($tcURL, $tcMethod='GET', $toParameters = array(), $toOAuthUser = NULL)
	{
		$toParameters = $this->addParameters($tcMethod, $tcURL, $toParameters, $toOAuthUser);

		$loCurl = curl_init();
		curl_setopt($loCurl, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($loCurl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
	    	curl_setopt($loCurl, CURLOPT_TIMEOUT, $this->timeout);
	    	curl_setopt($loCurl, CURLOPT_RETURNTRANSFER, TRUE);

	    	$lcAuthHeader = 'Authorization: OAuth ';
	    	$lcQuery = '';
	    	foreach ($toParameters as $lcKey => $lcValue)
	    	{
	    		if (preg_match('/^oauth/', $lcKey))
	    		{
	    			$lcAuthHeader.=$lcKey.'="'.$lcValue.'", ';
	    		}
	    		else
	    		{
	    			$lcQuery.=(strpos($tcURL, '?') === FALSE && strpos($lcQuery, '?') === FALSE ? '?' : '&').$lcKey.'='.$lcValue;
	    		}
	    	}
	    	$lcAuthHeader = substr($lcAuthHeader, 0, strlen($lcAuthHeader)-2);

	    	curl_setopt($loCurl, CURLOPT_HTTPHEADER, array($lcAuthHeader,
	    		'Expect:'));
	    	curl_setopt($loCurl, CURLOPT_SSL_VERIFYPEER, $this->sslVerifyPeer);
	    	curl_setopt($loCurl, CURLOPT_HEADERFUNCTION, array($this, 'headerCallback'));
	    	curl_setopt($loCurl, CURLOPT_HEADER, FALSE);

	    	if ($tcMethod === 'POST')
	    	{
	    		curl_setopt($loCurl, CURLOPT_POST, TRUE);
			if (!is_null($toParameters))
			{
				curl_setopt($loCurl, CURLOPT_POSTFIELDS, $toParameters);
			}
		}

		curl_setopt($loCurl, CURLOPT_URL, $tcURL.$lcQuery);

		$this->m_oHTTPHeader = array();
		$loResponse = curl_exec($loCurl);

		$loReturn = array(
    			'responseCode'=>curl_getinfo($loCurl, CURLINFO_HTTP_CODE),
    			'info'=>curl_getinfo($loCurl),
    			'url'=>$tcURL,
    			'headers'=>$this->m_oHTTPHeader,
    			'response'=>$loResponse,
    			);
		curl_close($loCurl);
		$this->m_oHTTPHeader = array();

		return $loReturn['responseCode'] === 200 ? $loReturn : null;
	}



	private function sign($tcMethod, $tcURL, $toParameters, $toOAuthUser = NULL)
	{
		return $this->getSignatureMethod()->sign($tcMethod, $tcURL, $toParameters, $this->getConsumerSecret(), is_null($toOAuthUser) ? '' : $toOAuthUser->Secret);
	}

	private function getSignatureMethod()
	{
		if (is_null($this->m_oSignatureMethod))
		{
			$this->m_oSignatureMethod = new SignatureHMACSHA1();
		}
		return $this->m_oSignatureMethod;
	}

	protected function getTimeStamp()
	{
		return time();
	}

	protected function getNonce()
	{
		return Utilities::getStringGUID();
	}

	private function getConsumerSecret()
	{
		return Yii::app()->params['twitter']['consumerSecret'];
	}
	private function getConsumerKey()
	{
		return Yii::app()->params['twitter']['consumerKey'];
	}

	public function parseParameterString($tcString)
	{
		$laReturn = explode('%', $tcString);
		return $laReturn;
	}

	protected function addParameters($tcMethod, $tcURL, $toParameters, $toOAuthUser = NULL)
	{
		if (!is_null($toOAuthUser))
		{
			$toParameters['oauth_token']=$toOAuthUser->Token;
		}
		$toParameters['oauth_consumer_key']=$this->getConsumerKey();
		$toParameters['oauth_nonce']=$this->getNonce();
		$toParameters['oauth_signature_method']=$this->getSignatureMethod()->getName();
		$toParameters['oauth_timestamp']=$this->getTimeStamp();
		$toParameters['oauth_version']=$this->version;

		return $this->sign($tcMethod, $tcURL, $toParameters, $toOAuthUser);
	}

	protected function headerCallback($toCurlSession, $tcData)
	{
		$lnIndex = strpos($tcData, ':');
		if ($lnIndex > 0)
		{
			$this->m_oHTTPHeader[str_replace('-', '_', strtolower(substr($tcData, 0, $lnIndex)))] = trim(substr($tcData, $lnIndex + 2));
		}
		return strlen($tcData);
	}
}

?>