<?php

class Twitter extends OAuth
{
	public $host='https://api.twitter.com';

	public $endpointURLs = array(
		'authenticate'=>array(
			'url'=>'/oauth/authenticate',
			'method'=>'get',
			'type'=>'redirect',),
		'authorize'=>array(
			'url'=>'/oauth/authorize',
			'method'=>'get'),

		'access'=>array(
			'url'=>'/oauth/access_token',
			'method'=>'post'),
		'request'=>array(
			'url'=>'/oauth/request_token',
			'method'=>'get',),
		'userinfo'=>array(
			'url'=>'https://api.twitter.com/1/users/show.json',
			'method'=>'get'),
        'postStatus'=>array(
            'url'=>'https://api.twitter.com/1.1/statuses/update.json',
            'method'=>'post'),
		);


	public function getProviderName()
	{
		return 'Twitter';
	}

	public function postTweet($toAuthUser, $tcTweet)
	{
        require dirname(__FILE__).'/tmhOAuth.php';
        require dirname(__FILE__).'/tmhUtilities.php';
        $tmhOAuth = new tmhOAuth(array(
            'consumer_key'    => $this->getConsumerKey(),
            'consumer_secret' => $this->getConsumerSecret(),
            'user_token'      => $toAuthUser->Token,
            'user_secret'     => $toAuthUser->Secret,
        ));

        $code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
            'status' => $tcTweet
        ));

/*
        if ($code == 200)
        {
            Utilities::printVar(json_decode($tmhOAuth->response['response']));
        } else
        {
            Utilities::printVar($tmhOAuth->response);
        }
*/

        return NULL;

        // TODO: Fix OAUTH
		$loResponse = $this->makeRequest($this->getEndpoint('postStatus'), array(
			'status'=>$tcTweet), $toAuthUser, true);



		if ($loResponse != null)
		{
			return json_decode($loResponse['response']);
		}
		return NULL;
	}

	protected function getConfirmationParameter()
	{
		return 'oauth_callback_confirmed';
	}

	protected function getVerificationParameter()
	{
		return 'oauth_verifier';
	}

	protected function getTokenParameter()
	{
		return 'oauth_token';
	}

	protected function getSecretParameter()
	{
		return 'oauth_token_secret';
	}

	protected function isOAuthConfirmed($toRequest)
	{
		return isset($toRequest['oauth_callback_confirmed']) && $toRequest['oauth_callback_confirmed'] === 'true';
	}

	protected function isOAuthVerified($toRequest)
	{
		return isset($toRequest['oauth_verifier']);
	}

	protected function addAccessTokenParameters($toParameters, $toOAuthUser)
	{
		return $toParameters;
	}

	protected function getUserForAccessRequest($taParameters)
	{
		return $this->getUserForToken($taParameters['oauth_token']);
	}

	protected function updateOAuthUserInfo($taParameters, $toOAuthUser)
	{
		$toOAuthUser->setAttributes(Array(
			'Token'=>$taParameters['oauth_token'],
			'Secret'=>$taParameters['oauth_token_secret'],
			'Expires'=>Utilities::getTimestamp() * (1000 * 60 * 60 * 24 * 356),
			'UID'=>$taParameters['user_id'],
			'DisplayName'=>$taParameters['screen_name'],
			'UserName'=>$taParameters['screen_name'],
			));
		return $taParameters;
	}

	public function getUserInfo($toOAuthUser)
	{
		$loParameters = array('screen_name'=>$toOAuthUser->UserName,);
		$loRequest = $this->makeRequest($this->getEndpoint('userinfo'), $loParameters, NULL);

		return json_decode($loRequest['response'], true);
	}

	protected function beginCreateUser($toOAuthUser, $toExtraInfo)
	{
		// We can't access email from Twitter so request it from the user
        $_SESSION['OAuthUser'] = $toOAuthUser->OAuthUserID;
        Yii::app()->getController()->redirect('/retrieveEmail');
	}

	protected function populateUserInfo($toUser, $toUserInfo, $toOAuthUser, $toExtraInfo)
	{
		$laName = explode(' ', !is_null($toExtraInfo) ? strtoupper($toExtraInfo['name']): $toUser->DisplayName);
		if (count($laName) < 2)
		{
			$laName[1]='';
		}

		$toUserInfo->ProfileImageURI = !is_null($toExtraInfo) ? $toExtraInfo['profile_image_url'] : NULL;
		$toUserInfo->FirstName = $laName[0];
		$toUserInfo->LastName = $laName[count($laName)-1];
		$toUserInfo->Description = !is_null($toExtraInfo) ? $toExtraInfo['description'] : '';

		/*
		[id] => 23741402
		    [id_str] => 23741402
		    [name] => Ken McHugh
		    [screen_name] => kmchugh12
		    [location] => Singapore
		    [url] => 
		    [description] => Co-founder of YouCommentate.  Fan-sourced live audio commentary.  Call it as you see it.
		    [protected] => 
		    [followers_count] => 198
		    [friends_count] => 345
		    [listed_count] => 6
		    [created_at] => Wed Mar 11 09:34:29 +0000 2009
		    [favourites_count] => 30
		    [utc_offset] => 28800
		    [time_zone] => Singapore
		    [geo_enabled] => 
		    [verified] => 
		    [statuses_count] => 936
		    [lang] => en
		    [status] => Array
		        (
		            [created_at] => Tue Nov 06 17:12:24 +0000 2012
		            [id] => 2.65864219911E+17
		            [id_str] => 265864219910602752
		            [text] => RT @DashBurst: 10 Reasons Why Your Website Redo Will Fail. http://t.co/DpJCsKOB
		            [source] => Buffer
		            [truncated] => 
		            [in_reply_to_status_id] => 
		            [in_reply_to_status_id_str] => 
		            [in_reply_to_user_id] => 
		            [in_reply_to_user_id_str] => 
		            [in_reply_to_screen_name] => 
		            [geo] => 
		            [coordinates] => 
		            [place] => 
		            [contributors] => 
		            [retweet_count] => 0
		            [favorited] => 
		            [retweeted] => 
		            [possibly_sensitive] => 
		        )

		    [contributors_enabled] => 
		    [is_translator] => 
		    [profile_background_color] => C0DEED
		    [profile_background_image_url] => http://a0.twimg.com/images/themes/theme1/bg.png
		    [profile_background_image_url_https] => https://si0.twimg.com/images/themes/theme1/bg.png
		    [profile_background_tile] => 
		    [profile_image_url] => http://a0.twimg.com/profile_images/2222641787/profile-sq_normal.jpg
		    [profile_image_url_https] => https://si0.twimg.com/profile_images/2222641787/profile-sq_normal.jpg
		    [profile_link_color] => 0084B4
		    [profile_sidebar_border_color] => C0DEED
		    [profile_sidebar_fill_color] => DDEEF6
		    [profile_text_color] => 333333
		    [profile_use_background_image] => 1
		    [default_profile] => 1
		    [default_profile_image] => 
		    [following] => 
		    [follow_request_sent] => 
		    [notifications] => 
		 */
	}

	protected function populateUser($toUser, $toOAuthUser, $toExtraInfo)
	{
                    // Update the User
                    /*
                    if (!is_null($loOAuthInfo))
                    {
                        $loUser->DisplayName = $loOAuthInfo->screen_name;
                        $loUser->save();
                    }

                    $laName = explode(' ', !is_null($loOAuthInfo) ? strtoupper($loOAuthInfo->name): $loUser->DisplayName);
                    if (count($laName) < 2)
                    {
                        $laName[1]='';
                    }

                    $loUserInfo->UserID=$loUser->UserID;
                    $loUserInfo->Country = !is_null($loOAuthInfo) ? strtoupper($loOAuthInfo->location) : NULL;
                    $loUserInfo->ProfileImageURI = !is_null($loOAuthInfo) ? $loOAuthInfo->profile_image_url : NULL;
                    $loUserInfo->FirstName = $laName[0];
                    $loUserInfo->LastName = $laName[count($laName)-1];
                    $loUserInfo->Description = !is_null($loOAuthInfo) ? $loOAuthInfo->description : '';
                    $loUserInfo->save();

                    $this->addErrors($loUserInfo->getErrors());

                    $loOAuthUser->UserID=$loUser->UserID;
                    $loOAuthUser->UserGUID=$loUser->GUID;
                    $loOAuthUser->UserName=$loUser->Email;
                    $loOAuthUser->save();

                    $loUserIdentity=new PlinthUserIdentity($loUser->Email,$loUser->Password);
                    Yii::app()->user->login($loUserIdentity,3600*24*30);
                    */
	}

	protected function parseEmail($taParameters)
	{
		return isset($taParameters['email']) ? urldecode($taParameters['email']) : '';
	}

	protected function processParameters($toParameters)
	{
		return $toParameters;
	}

}

?>