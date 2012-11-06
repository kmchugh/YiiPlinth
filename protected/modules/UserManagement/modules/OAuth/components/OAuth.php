<?php

abstract class OAuth
{
	public $userAgent;
	public $timeout=30;
	public $sslVerifyPeer = FALSE;
	public $version="1.0";
	public $permissions = NULL;


	private $m_oHTTPHeader;
	private $m_oSignatureMethod = NULL;


	public $endpointURLs = array(
		'authenticate'=>'oauth/authenticate',
		'authorize'=>'oauth/authorize',
		'access'=>'oauth/access_token',
		'request'=>'oauth/request_token',
		);

	/**
	 * OAuth Constructor
	 */
	public function __construct()
	{
		$this->userAgent=Yii::app()->name.' HTTP AGENT 1.1';
	}

	/**
	 * Checks if an API Key has been set for this provider
	 * @return boolean true if an API key has been specified for this provider
	 */
	public function hasAPIKey()
	{
		return isset(Yii::app()->params[strtolower($this->getProviderName())]) &&
			isset(Yii::app()->params[strtolower($this->getProviderName())]['consumerSecret']) &&
			isset(Yii::app()->params[strtolower($this->getProviderName())]['consumerKey']);
	}

	/**
	 * Gets the name of the parameters that is used as verification by the service
	 * @return string the name of the parameter to check for the verification code
	 */
	protected abstract function getVerificationParameter();

	/**
	 * Gets the name of the parameters that is used as token by the service
	 * @return string the name of the parameter to check for the token
	 */
	protected abstract function getTokenParameter();

	/**
	 * Updates the parameter list for the service
	 * @param  array the parameters to send to the service
	 * @return array the updated parameter list
	 */
	protected abstract function processParameters($toParameters);

	/**
	 * Gets the name of this provider
	 * @return string the provider name
	 */
	public abstract function getProviderName();

	/**
	 * Checks if the oauth request has been accepted by the user
	 * @param  Object the response from the oauth service
	 * @return boolean true if the user accepted the request
	 */
	protected abstract function isOAuthVerified($toRequest);

	/**
	 * Gets the OAuth user for the access request
	 * @param  array the parameters parsed from the request
	 * @return OAuthUser the user from the request
	 */
	protected abstract function getUserForAccessRequest($taParameters);

	/**
	 * Updates the OAuthUser with the information from the request
	 * @param  array the parameters parsed from the request
	 * @param  array the OAuthUser to update
	 * @return OAuthUser the updated user
	 */
	protected abstract function updateOAuthUserInfo($taParameters, $toOAuthUser);

	/**
	 * Add parameters to the access token request
	 * @param array the parameter list
	 * @param OAuthUser the oauth user
	 * @return the updated parameters
	 */
	protected function addAccessTokenParameters($toParameters, $toOAuthUser)
	{
		return $toOAuthUser;
	}

	/**
	 * Populates the user information for the newly created user
	 * @param  User $loUser      The user to populate the information for
	 * @param  OAuthUser $toOAuthUser The OAuthUser the user is being created for
	 * @param  array $toExtraInfo the parameters with the extra information to populate the user with
	 * @return boolean              true if everything was okay
	 */
	protected abstract function populateUser($toUser, $toOAuthUser, $toExtraInfo);

	/**
	 * Extracts and returns the email address from the parameters
	 * @param  array $taParameters the parameters to extract the email address from
	 * @return string               the email address
	 */
	protected abstract function parseEmail($taParameters);

	/**
	 * Handles the respons from the OAuth access request
	 * @param  Object the request from the OAuth provider
	 * @return true if the user is logged in as a result of this call
	 */
	public function handleOAuthResponse($toRequest)
	{
		if (isset($toRequest[$this->getVerificationParameter()]))
		{
			$loValues = $this->updateAccessToken($_REQUEST[$this->getTokenParameter()], $_REQUEST[$this->getVerificationParameter()]);
			$loOAuthUser = $loValues['user'];
			$loExtraInfo = $loValues['extrainfo'];

			// Determine if this is a new user or an old user
			if (!isset($loAuthUser->UserID))
			{
				// This is a new user so we need to create one
				$loUser = $this->beginCreateUser($loOAuthUser, $loExtraInfo);
			}
			Yii::app()->getController()->redirect(preg_match('/.+?\/login/', Utilities::getCallbackURL()) ? '/' : Utilities::getCallbackURL());
			return true;
		}
		return false;
	}

	/**
	 * Starts the process of creating a new user
	 * @param  Object $toOAuthUser The OAuthUser to create the User from
	 * @param  array $toExtraInfo an array of additional information which can be used to create the user
	 */
	protected function beginCreateUser($toOAuthUser, $toExtraInfo)
	{
		$loUser = $this->createUser($toOAuthUser, $this->parseEmail($toExtraInfo), $toExtraInfo);
		$loUser->save();
	}

	/**
	 * Creates a user for the specified AuthUser
	 * @return OAuthUser the Auth User to create the user for
	 */
	public function createUser($toOAuthUser, $tcEmail, $toExtraInfo)
	{
		if(is_null($toOAuthUser))
		{
			echo "its all gone horribly wrong";
			exit();
		}
		$loUser = User::create($tcEmail);
		$loUser->DisplayName = $toOAuthUser->DisplayName;

		$lnCount = 0;
		while (!$loUser->save() && $lnCount < 10)
		{
			if ($loUser->getError('DisplayName'))
			{
				$loUser->DisplayName = $toOAuthUser->DisplayName.str($lnCount+1);
			}
			else
			{
				$lnCount = 11;
			}

			// If this error occurs then the user is already registered so link them up
			if ($loUser->getError('Email'))
			{
				$loUser = User::model()->findByAttributes(array(
					'Email' => $loUser->Email,));
				break;
			}
			$lnCount+=1;
		}

		if (!$loUser->hasErrors())
		{
			if (!is_null($toExtraInfo))
			{
				$this->populateUser($loUser, $toOAuthUser, $toExtraInfo);
				$loUser->save();
			}
		}

		$toOAuthUser->UserID=$loUser->UserID;
		$toOAuthUser->UserGUID=$loUser->GUID;
		$toOAuthUser->save();

		$this->loginUser($toOAuthUser->user);

		return $loUser;
                }

                /**
                 * Ensures the speicified user is authenticated
                 * @param  OAuthUserr $toUser The user being authenticated
                 * @return true if the user is successfully authenticated
                 */
	private function loginUser($toUser)
	{
		if (!is_null($toUser))
		{
			$loUserIdentity=new PlinthUserIdentity($toUser->Email,$toUser->Password);
			Yii::app()->user->login($loUserIdentity,3600*24*30);

			// TODO: Refactor this to be an event
			$toUser->LoginCount = $toUser->LoginCount+1;
			$toUser->LastLoginDate = Utilities::getTimestamp();
			$toUser->save();
			return true;
		}
		return false;
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
		$loExtraInfo = array();
		if (!is_null($loOAuthUser))
		{
			$loParameters = array();
			$loParameters = $this->addAccessTokenParameters($loParameters, $loOAuthUser);
			$loOAuthUser->Verified = true;
			$loOAuthUser->Token = $tcToken;
			$loParameters['oauth_verifier']=$tcVerifier;

			$loRequest = $this->makeRequest($this->getEndpoint('access'), $loParameters, $loOAuthUser);
			if (!is_null($loRequest))
			{
				$laParameters = array();
				parse_str($loRequest['response'], $laParameters);

				$loUser = $this->getUserForAccessRequest($laParameters);
				if ($loUser != null && $loUser !== $loOAuthUser)
				{
					$loOAuthUser->delete();
					$loOAuthUser = $loUser;
				}
				$loExtraInfo = $this->updateOAuthUserInfo($laParameters, $loOAuthUser);
				$loUser = OAuthUser::model()->findByAttributes(array(
					'UID' => $loOAuthUser->UID,
					'Provider'=>$this->getProviderName()));
				if ($loUser != null && $loUser !== $loOAuthUser)
				{
					$loUser->Token = $loOAuthUser->Token;
					$loUser->Secret = $loOAuthUser->Secret;
					$loUser->Expires = $loOAuthUser->Expires;
					$loOAuthUser->delete();
					$loOAuthUser = $loUser;
				}
			}
 			$loOAuthUser->save();
		}
		return array(
			'user'=>$loOAuthUser,
			'extrainfo'=>$loExtraInfo,
			);
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

		// Add in the permissions we want to request
		if (!is_null($this->permissions))
		{
			$loParameters['oauth_permissions']=$this->permissions;
		}

		// Add in the nonce so we can retrieve it
		$loParameters['oauth_nonce']=$this->getNonce();

		// Create an unverified OAuthUser record
		$loOAuth = new OAuthUser();
		$loOAuth->setAttributes(Array(
			'Provider'=>$this->getProviderName(),
			'Verified'=>false,
			'Token'=>$loParameters['oauth_nonce'],
			'Secret'=>$toCallbackURL,
			));
		if ($loOAuth->save())
		{
			$this->handleOAuthResponse($this->makeRequest($this->getEndpoint('request'), $loParameters));
		}
		else
		{
			echo "Unable to save OAuth";
		}
	}

	/**
	 * Gets the user with the specified token
	 * @param  string $tcToken The token that we are using to get the user
	 * @return OAuthUser object, or null if none was found
	 */
	protected function getUserForToken($tcToken)
	{
		return OAuthUser::model()->findByAttributes(array(
			'Token' => $tcToken,
			'Provider'=>$this->getProviderName()));
	}

	/**
	 * Ensures the correct endpoint url will be used, this will return the host + endpoint if the endpoint does not
	 * include http or https otherwise it will just return the endpoint
	 * @param  String $tcType The end point that is being retrieved
	 * @return String the full enpoint url
	 */
	protected function getEndpoint($tcType)
	{
		$laEndpoint = $this->endpointURLs[$tcType];
		$laReturn = array();
		foreach ($laEndpoint as $lcKey => $loValue) 
		{
			$laReturn[$lcKey] = $lcKey === 'url' ? 
				(preg_match('/^https?:\/\/.+/i', $loValue) ? $loValue : $this->host.$loValue) :
				$laReturn[$lcKey] = $loValue;
		}
		return $laReturn;
	}

	/**
	 * Builds the query string based on the endpoint and parameters
	 * @return [type] [description]
	 */
	protected function buildQueryString($toEndpoint, $toParameters)
	{
		if ($toEndpoint['method'] === 'get')
		{
			$lcURL = $toEndpoint['url'];
			foreach ($toParameters as $lcKey => $lcValue) 
			{
				$lcURL.=(strpos($lcURL, '?') === FALSE && strpos($lcURL, '?') === FALSE ? '?' : '&').$lcKey.'='.$lcValue;
			}
			return $lcURL;
		}
		return $toEndpoint['url'];
	}

	/**
	 * Makes the request to the OAuth client, returns the response if the call was successful
	 * @param  string $tcURL        The URL of the OAuth client endpoint
	 * @param  string $tcMethod     The http method to use to make the call.  Usually GET or POST
	 * @param  array  $toParameters The list of parameters
	 * @return array a response object
	 */
	protected function makeRequest($toEndpoint, $toParameters = array(), $toOAuthUser = NULL, $tlReturnError = FALSE)
	{
		$toParameters = $this->addParameters($toEndpoint['method'], $toEndpoint['url'], $toParameters, $toOAuthUser);

		$lcURL = $toEndpoint['method'] === 'get' ? $this->buildQueryString($toEndpoint, $toParameters) : $toEndpoint['url'];

		if (isset($toEndpoint['type']) && $toEndpoint['type'] == 'redirect')
		{
			Yii::app()->getController()->redirect($lcURL);
		}
		else
		{
			$loCurl = curl_init();
			curl_setopt($loCurl, CURLOPT_USERAGENT, $this->userAgent);
			curl_setopt($loCurl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
		    	curl_setopt($loCurl, CURLOPT_TIMEOUT, $this->timeout);
		    	curl_setopt($loCurl, CURLOPT_RETURNTRANSFER, TRUE);

		    	$lcAuthHeader = 'Authorization: OAuth ';
		    	//$lcQuery = '';
		    	foreach ($toParameters as $lcKey => $lcValue)
		    	{
		    		if (preg_match('/^oauth/', $lcKey))
		    		{
		    			$lcAuthHeader.=$lcKey.'="'.$lcValue.'", ';
		    		}
		    		else
		    		{
		    			//$lcQuery.=(strpos($tcURL, '?') === FALSE && strpos($lcQuery, '?') === FALSE ? '?' : '&').$lcKey.'='.$lcValue;
		    		}
		    	}

		    	$lcAuthHeader = substr($lcAuthHeader, 0, strlen($lcAuthHeader)-2);

		    	curl_setopt($loCurl, CURLOPT_HTTPHEADER, array($lcAuthHeader,
		    		'Expect:'));
		    	curl_setopt($loCurl, CURLOPT_SSL_VERIFYPEER, $this->sslVerifyPeer);
		    	curl_setopt($loCurl, CURLOPT_HEADERFUNCTION, array($this, 'headerCallback'));
		    	curl_setopt($loCurl, CURLOPT_HEADER, FALSE);

		    	if ($toEndpoint['method'] === 'POST')
		    	{
		    		curl_setopt($loCurl, CURLOPT_POST, TRUE);
				if (!is_null($toParameters))
				{
					curl_setopt($loCurl, CURLOPT_POSTFIELDS, $toParameters);
				}
			}

			curl_setopt($loCurl, CURLOPT_URL, $lcURL);//.$lcQuery);

			$this->m_oHTTPHeader = array();
			$loResponse = curl_exec($loCurl);

			$loReturn = array(
	    			'responseCode'=>curl_getinfo($loCurl, CURLINFO_HTTP_CODE),
	    			'info'=>curl_getinfo($loCurl),
	    			'url'=>$lcURL,
	    			'headers'=>$this->m_oHTTPHeader,
	    			'response'=>$loResponse,
	    			);
			curl_close($loCurl);
			$this->m_oHTTPHeader = array();
			return $loReturn['responseCode'] === 200 || $tlReturnError ? $loReturn : null;
		}
		return NULL;
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
		return Utilities::getStringGUID().'_'.$this->getProviderName();
	}

	protected function getConsumerSecret()
	{
		return Yii::app()->params[strtolower($this->getProviderName())]['consumerSecret'];
	}
	private function getConsumerKey()
	{
		return Yii::app()->params[strtolower($this->getProviderName())]['consumerKey'];
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
		if (!isset($toParameters['oauth_nonce']))
		{
			$toParameters['oauth_nonce']=$this->getNonce();
		}
		$toParameters['oauth_consumer_key']=$this->getConsumerKey();
		$toParameters['oauth_signature_method']=$this->getSignatureMethod()->getName();
		$toParameters['oauth_timestamp']=$this->getTimeStamp();
		$toParameters['oauth_version']=$this->version;

		$toParameters = $this->processParameters($toParameters);

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