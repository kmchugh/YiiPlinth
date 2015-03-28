<?php
	abstract class Utilities
	{
        /**
         * Checks if this session is a mobile session or desktop
         * @return bool true if this is a mobile session, false otherwise
         */
        public static function isMobile()
        {
            if (is_set(Yii::app()->session['mobileSession']))
            {
                return Yii::app()->session['mobileSession'];
            }

            $lcAgent = $_SERVER['HTTP_USER_AGENT'];
            Yii::app()->session['mobileSession'] = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($lcAgent,0,4));
            
            return Yii::app()->session['mobileSession'];
        }

        /**
         * Retrieves and processes the image
         * @param $toModel the model that we are extracting the attribute for
         * @param $tcAttribute the attribute the image is to be attached to
         * @param $tcImageDirectory the directory to store the image to
         * @param null $taSize the size of the image if it is to be resized (array('width', 'height')
         * @param null $tcImageName the name of the image if it is to be renamed, otherwise null
         * @return null|string the URL of the image or null if no image could be processed
         */
        public static function processImage($toModel, $tcAttribute, $tcImageDirectory, $taSize = NULL, $tlDeleteOriginal = true, $tcImageName = NULL)
        {
            $loImage = CUploadedFile::getInstance($toModel, $tcAttribute);
            if (!is_null($loImage) && $loImage instanceof CUploadedFile)
            {
                // Make sure the storage directory exists
                if (!is_dir($tcImageDirectory))
                {
                    mkdir($tcImageDirectory, 0777, true);
                }
                $lcImageName = $tcImageDirectory.(is_null($tcImageName) ? $loImage->name : $tcImageName).'.'.$loImage->extensionName;

                // Make sure the file does not already exist
                if (is_file($lcImageName))
                {
                    unlink($lcImageName);
                }

                // Attempt to save the File
                if ($loImage->saveAs($lcImageName, $tlDeleteOriginal))
                {
                    if (!is_null($taSize))
                    {
                        // Resize the image
                        $loImage = Yii::app()->image->load($lcImageName);
                        $loImage->resize($taSize['width'], $taSize['height']);
                        $loImage->save();
                    }
                    return Yii::app()->assetManager->publish($lcImageName);
                }
                return null;
            }
            return $toModel[$tcAttribute];
        }


		/**
		* Returns true if this request was in development mode
		**/
		public static function isDevelopment()
		{
			$lcKey = $_SERVER['SERVER_NAME'].'_developmentRequest';
			if (!isset($GLOBALS[$lcKey]))
			{
				// Assume that a server with localhost or ending with .dev is a dev machine
				$GLOBALS[$lcKey] = preg_match('/^(http:\/\/)?localhost(.+)?|\.dev$/i', $_SERVER['SERVER_NAME']) > 0;

				if ($GLOBALS[$lcKey])
				{
					defined('YII_DEBUG') or define('YII_DEBUG',true);
					// specify how many levels of call stack should be shown in each log message
					defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
				}
			}
			return $GLOBALS[$lcKey];
		}

        /**
         * Gets a path relative from the $tcFrom directory to the $tcTo directory
         * @param $tcFrom the directory to start from
         * @param $tcTo the destination directory
         */
        public static function getRelativePath($tcFrom, $tcTo)
        {
            $laFrom=explode(DIRECTORY_SEPARATOR, realpath($tcFrom));
            $laTo=explode(DIRECTORY_SEPARATOR, realpath($tcTo));
            $lnFrom=count($laFrom);
            $lnTo=count($laTo);

            $lnCounter=0;
            for (;$lnCounter<$lnFrom && $lnCounter<$lnTo;$lnCounter++)
            {
                // Find where the paths deviate
                if ($laFrom[$lnCounter]!==$laTo[$lnCounter])
                {
                    break;
                }
            }

            // Update the from
            for ($i=$lnCounter;$i<$lnFrom;$i++)
            {
                $laFrom[$i]='..';
            }
            for ($i=$lnCounter;$i<$lnTo;$i++)
            {
                $laFrom[]=$laTo[$i];
            }

            return implode(DIRECTORY_SEPARATOR,array_slice($laFrom, $lnCounter));
        }

        /**
         * Returns a url suitable for use in URLS
         * @param $tcURL the url to encode
         */
        public static function SEOEncode($tcURL)
        {
            return rawurldecode(str_replace(' ', '_', $tcURL));
        }

		/**
		* Includes the files specified, and if the return value from the
		* file specified is an array, merges the results in the order given
		* in the array.
		* If the file does not exist it is skipped over
		* If the file does not return an array, the result of that specific
		* file is not merged, but the process continues to attempt the remaining
		* files.
		**/
		public static function mergeIncludedArray($taFileList)
		{
			$loConfig = array();
			foreach ($taFileList as $laFile)
			{
				if (file_exists($laFile))
				{
					$loReturn = include($laFile);
					if (gettype($loReturn) === 'array')
					{
						$loConfig = self::override($loConfig, $loReturn);
					}
				}
			}
			return $loConfig;
		}

		/**
		 * Override merge two arrays, if elements exist in the taBase those elements will be overridden
		 * by the equivalent elements in $taOverride.  this is a recursive function
		 * @param  array $taBase  the base values
		 * @param  array $taOverride the values to override with
		 * @return array an array where all of the values from taOverride have been incorporated
		 */
		public static function override($taBase, $taOverride)
		{
			if (!is_array($taBase))
			{
				return $taOverride;
			}
			if (!is_array($taOverride))
			{
				$taBase[] = $taOverride;
			}

			foreach ($taOverride as $lcKey => $lcValue)
			{
				if (is_numeric($lcKey))
				{
					if (!in_array($lcValue, $taBase))
					{
						$taBase[] = $lcValue;
					}
				}
				else
				{
					$taBase[$lcKey] = isset($taBase[$lcKey]) ?
						self::override($taBase[$lcKey], $taOverride[$lcKey]) :
						$taOverride[$lcKey];
				}
			}
			return $taBase;
		}

		/**
		 * Updates the callback url if one has not already been set.  This will set the callback url
		 * to be the referrer
		 */
		public static function updateCallbackURL()
		{
			$_SESSION['forwardToURL'] = !isset($_SESSION['forwardToURL']) || is_null($_SESSION['forwardToURL']) ? (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL ) : $_SESSION['forwardToURL'];
		}

		/**
		 * Sets the callback url when a redirect is needed
		 * @param the callback url
		 */
		public static function setCallbackURL($tcURL)
		{
			$_SESSION['forwardToURL'] = $tcURL;
		}

		/**
		 * Gets the current callback url
		 * @return the callback url
		 */
		public static function getCallbackURL()
		{
			return isset($_SESSION['forwardToURL']) ? $_SESSION['forwardToURL'] : NULL;
		}

		/**
		* returns the first non null parameter
		**/
		public static function ISNULL()
		{
			foreach (func_get_args() as $loArg)
			{
				if (!is_null($loArg))
				{
					return $loArg;
				}
			}
			return NULL;
		}

		/**
		* Returns the first non null non empty (for strings) parameter
		**/
		public static function ISNULLOREMPTY()
		{
			foreach (func_get_args() as $loArg)
			{
				if (!is_null($loArg) && (is_string($loArg) && (!strlen($loArg) == 0)))
				{
					return $loArg;
				}
			}
			return NULL;
		}

		/**
		 * Gets the specified String translated in the current context.  If the string does not exist, then the
		 * key will be returned
		 * @param  string $tcKey      The key or string to translate
		 * @param  string $tcCategory The category of the string to retrieve
		 * @return string the translated string, or $tcKey if the translation did not exist
		 */
		public static function getString($tcKey, $tcCategory = 'app')
		{
			return Yii::t($tcCategory, Yii::t($tcCategory, $tcKey, NULL, NULL, 'default'));
		}

		/**
		* Checks if a string ends with the specified string
		* returns true if the string ends with the specified string, false otherwise
		**/
		public static function endsWith($tcSearchIn, $tcSearchFor, $tlCaseInsensitive = false)
		{
			return Utilities::startsWith(strrev($tcSearchIn), strrev($tcSearchFor), $tlCaseInsensitive);
		}

		/**
		* Checks if a string starts with the specified string
		* returns true if the string starts with the specified string, false otherwise
		**/
		public static function startsWith($tcSearchIn, $tcSearchFor, $tlCaseInsensitive = false)
		{
			return strpos(
				$tlCaseInsensitive ? strtolower($tcSearchIn) : $tcSearchIn,
				$tlCaseInsensitive ? strtolower($tcSearchFor) : $tcSearchFor
				) === 0;
		}

		/**
		* Gets the localised version of the date for the user
		**/
		public static function dateftime($tnMillis)
		{
			// TODO: Allow this to output javascript code for converting timestamp for local time,
			// or convert the time to the users region

			return $tnMillis <= 0 ? '' : @strftime('%d %b %Y %I:%M:%m %Z', $tnMillis / 1000);
		}

		/**
		* Outputs the contents of the variable
		**/
		public static function printVar($toVariable, $tlReturn = false)
		{
            if ($tlReturn)
            {
                return ((is_null($toVariable) || !isset($toVariable)) ? 'NULL' : print_r($toVariable, 1));
            }
            echo '<pre>'.((is_null($toVariable) || !isset($toVariable)) ? 'NULL' : print_r($toVariable, 1)).'</pre>';
		}

		/**
		* Implodes an array, using the key and value to generate a string.
		* The resulting string would look like the following:
		* {key}{glue}{value}{value separator}
		**/
		public static function array_implode($tcGlue = '=', $tcValueSeparator = ', ' , array $taArray)
		{
			$laReturn = array();
			foreach ($taArray as $lcKey => $lcValue)
			{
				$laReturn[] = "{$lcKey}{$tcGlue}{$lcValue}";
			}
			return implode($tcValueSeparator, $laReturn);
		}

		/**
		* Gets the date in a format suitable for HTTP,
		* tnTimestamp is the numeric long representation of a date
		**/
		public static function getRFC1123Date($tnTimestamp)
		{
			return gmdate('r', $tnTimestamp);
		}

		/**
		* Returns a unique identifier
		**/
		public static function getStringGUID()
		{
			return str_replace('.', '', uniqid('', TRUE));
		}

		/**
		* Retrieves a timestamp based on the current time.
		* @return float the current time as milliseconds.microseconds
		**/
		public static function getTimestamp()
		{
			list($laUsec, $laSec) = explode(" ", microtime());
   			return ((float)$laUsec + ((float)$laSec * 1000.00));
		}

		public static function scientificToLong($tnScientific)
		{
			return !is_null($tnScientific) ?
			 number_format($tnScientific, 0, '.', '') :
			 0;
		}

		/**
		 * Checks if the file exists, if the file does exist returns the case
		 * insensitive name of the file.  If the file does not exist this function
		 * will return null
		 * @param  String  $tcFileName      The name of the file to get
		 * @param  boolean $tcCaseSensitive if true check case insensitively
		 * @return String the proper case filename or null if the file could not be found
		 */
		public static function fileExists($tcFileName, $tcCaseSensitive = true)
		{
			if (file_exists($tcFileName))
			{
				return $tcFileName;
			}

			if (!$tcCaseSensitive)
			{
				$lcDir = dirname($tcFileName);
				$laFiles = glob($dir.'/*');
				$lcFileName = strtolower($tcFileName);
				foreach ($laFiles as $lcFile)
				{
					return $lcFile;
				}
			}
			return NULL;
		}

		public static function array_change_key_case_recursive(array $taInput, $tnCase = CASE_LOWER)
		{
			$loReturn = array();
			foreach($taInput as $lcKey=>$loValue)
			{
				if (!is_array($taInput[$lcKey]))
				{
					$loReturn[$tnCase === CASE_UPPER ? mb_strtoupper($lcKey) : mb_strtolower($lcKey)] =
						$loValue;
				}
				else
				{
					$loReturn[$tnCase === CASE_UPPER ? mb_strtoupper($lcKey) : mb_strtolower($lcKey)]=self::array_change_key_case_recursive($loValue, $tnCase);
				}
			}
			return $loReturn;
		}

		/**
		* Checks if the specified entity exists in the db specified by the connection.
		* This check is case insensitive.
		* @param $toConnection the database connection to check
		* @param $tcTableName the name of the table to check for
		**/
		public static function entityExists($toConnection, $tcTableName)
		{
			return Utilities::in_arrayi($tcTableName, $toConnection->Schema->TableNames);
		}

		/**
		* Checks if a value exists in an array, this is case insensitive
		**/
		public static function in_arrayi($tcNeedle, $taHaystack)
		{
			$tcNeedle = strtolower($tcNeedle);
			foreach ($taHaystack as $lcValue)
			{
				if (strtolower($lcValue) === $tcNeedle)
				{
					return true;
				}
			}
			return false;
		}

		/**
		* Checks if the specified module is installed, if so $toCallback is called.
		* If not and if $toFailure is not null, $toFailure is called.
		* This will return the result of $toCallback or $toFailure
		**/
		public static function ifModuleExists($tcModuleName, $toCallback, $toFailure = NULL)
		{
			return (Yii::app()->hasModule($tcModuleName)) ?
				$toCallback() :
				!is_null($toFailure) ?
                    (is_callable($toFailure) ? $toFailure() : NULL ):
					NULL;
		}

		/**
		 * Recursively removes the specified directory.  This will delete the directory and all containing
		 * files/folders.
		 * @param  String the directory to remove
		 * @return Boolean true on success
		 */
		public static function rrmdir($tcDirectory)
		{
			foreach (glob($tcDirectory.'/*') as $lcFile) 
			{
				if (is_dir($lcFile))
				{
					Utilities::rrmdir($lcFile);
				}
				else
				{
					unlink($lcFile);
				}
			}
			return rmdir($tcDirectory);
		}

		/**
		* Recursively merges two arrays, this will alter the value of $taArray
		**/
		public static function inline_array_merge_recursive(&$taArray, $taMerge)
		{
			foreach ($taMerge as $lcKey => $loValue)
			{
				if (isset($taArray[$lcKey]))
				{
					// Merge
					if (is_array($taArray[$lcKey]))
					{
						if (is_array($taMerge))
						{
							self::inline_array_merge_recursive($taArray[$lcKey], $loValue);
						}
						else
						{
							$taArray[$lcKey][] = $taMerge;
						}
					}
					else
					{
						// Convert to array then merge
						$loTemp = $taArray[$lcKey];
						$taArray[$lcKey] = array();
						$taArray[$lcKey][] = $loTemp;
						$taArray[$lcKey][] = $loValue;
					}
				}
				else
				{
					// New
					$taArray[$lcKey] = $loValue;
				}
			}
			return $taArray;
		}

		public static function aSplit($tcStringDelimiter, $tcAssociativeDelimiter, $tcString)
		{
			if (is_null($tcString) || strlen($tcString)==0)
			{
				return array();
			}
			$laReturn = array();
			preg_match_all('/([^'.$tcStringDelimiter.']+)'.$tcAssociativeDelimiter.'([^'.$tcStringDelimiter.']+)/', $tcString, $laReturn);
			return array_combine($laReturn[1], $laReturn[2]);
		}



		private static $g_oTwitter = NULL;

		/**
		* Authenticates in as the application and attempts to get the Authentication URL
		**/
		public static function getTwitterAuthenticationURL($tcCallbackURL = NULL)
		{
			$loLinks =  Yii::app()->getModule('UserManagement')->getModule('OAuth')->getOAuthProviderLinks();

			return $loLinks['Twitter'];
		}

        /**
         * Authenticates in as the application and attempts to get the Authentication URL
         **/
        public static function getFacebookAuthenticationURL($tcCallbackURL = NULL)
        {
            $loLinks =  Yii::app()->getModule('UserManagement')->getModule('OAuth')->getOAuthProviderLinks();

            return $loLinks['Facebook'];
        }

		// TODO: Refactor Twitter to separate class
		// TODO: Add an expiry time to OAuthUser which will force a recheck of the connection
		/**
		* Gets the Twitter oAuth object.  if tlConnectAsConsumer is true this will attempt to
		* connect as the consumer, otherwise it will connect as the client
		**/
		private static function getTwitterObject($tcCallbackURL = NULL, $tlConnectAsConsumer = TRUE)
		{
			// TODO: Remove this after all twitter functionality has been refactored
			Yii::import('YIIPlinth.modules.UserManagement.models.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.components.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.models.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.modules.Twitter.components.*');
			if (is_null($tcCallbackURL))
			{
				$tcCallbackURL = Yii::app()->params['twitter']['callbackURL'];
			}


			if (!$tlConnectAsConsumer)
			{
				// Update the user information
				$loAuthUser = OAuthUser::model()->findByAttributes(array('UserGUID'=>Yii::app()->user->GUID, 'Provider'=>'Twitter'));
				if (!is_null($loAuthUser))
				{
					$_SESSION['twitter_token'] = $loAuthUser->Token;
					$_SESSION['twitter_token_secret'] = $loAuthUser->Secret;
				}
			}

			// TODO: Consume TwitterOAuth can probably be cached globally, client may be able to be cached in the session
			$loTwitterObject = new TwitterOAuth(
				Yii::app()->params['twitter']['consumerKey'],
				Yii::app()->params['twitter']['consumerSecret'],
				$tlConnectAsConsumer ? NULL : $_SESSION['twitter_token'],
				$tlConnectAsConsumer ? NULL : $_SESSION['twitter_token_secret']);

			if ($tlConnectAsConsumer)
			{
				// TODO: Add in error handling
				Yii::app()->params['oauth_twitter'] = $loTwitterObject->getRequestToken($tcCallbackURL);
				$_SESSION['twitter_token']=Yii::app()->params['oauth_twitter']['oauth_token'];
				$_SESSION['twitter_token_secret']=Yii::app()->params['oauth_twitter']['oauth_token_secret'];
			}
			return $loTwitterObject;
		}

		/**
		* Handles the callback from Twitter and creates any records required
		**/
		public static function handleTwitterCallback()
		{
			// TODO: Remove this after all twitter functionality has been refactored
			Yii::import('YIIPlinth.modules.UserManagement.models.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.components.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.models.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.modules.Twitter.components.*');

			if( !empty($_GET['oauth_verifier']) &&
				!empty($_SESSION['twitter_token']) &&
				!empty($_SESSION['twitter_token_secret']))
			{
				$loTwitter = self::getTwitterObject(NULL, FALSE);
				if (!is_null($loTwitter))
				{
					$_SESSION['twitter_access_token'] = $loTwitter->getAccessToken($_GET['oauth_verifier']);

					$loAuthUser = self::getTwitterUser($loTwitter);
					if (!is_null($loAuthUser))
					{
						$loAuthUser->Token=$_SESSION['twitter_access_token']['oauth_token'];
						$loAuthUser->Secret=$_SESSION['twitter_access_token']['oauth_token_secret'];
						$loAuthUser->save();
					}
				}
				return $loAuthUser;
			}
			unset($_SESSION['twitter_token']);
			return NULL;
		}

		public static function sendTweet($tcMessage, $tcUSerGUID)
		{
			// TODO: Remove this after all twitter functionality has been refactored
			Yii::import('YIIPlinth.modules.UserManagement.models.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.components.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.models.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.modules.Twitter.components.*');

            // Only if the user is linked to a twitter account
			$loAuthUser = OAuthUser::model()->findByAttributes(array('UserGUID'=>$tcUSerGUID, 'Provider'=>'Twitter'));
			if (!is_null($loAuthUser))
			{
				$loOAuth = new Twitter();
				$loOAuth->postTweet($loAuthUser, $tcMessage);
			}
		}

        // TODO: Refactor the getTwitterUser and getFacebookUser to getOAuthUser
		public static function getTwitterUser($toTwitterObject = NULL)
		{
			// TODO: Remove this after all twitter functionality has been refactored
			Yii::import('YIIPlinth.modules.UserManagement.models.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.components.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.models.*');
			Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.modules.Twitter.components.*');

			// Update the user information
			$loAuthUser = OAuthUser::model()->findByAttributes(array('UserGUID'=>Yii::app()->user->GUID, 'Provider'=>'Twitter'));

			if (is_null($loAuthUser) && !is_null($toTwitterObject))
			{
				$loUserInfo = $toTwitterObject->get('account/verify_credentials');

				if (isset($loUserInfo->error))
				{
					echo $loUserInfo->error;
					$loUserInfo = null;
				}

				if ($loUserInfo != NULL)
				{
					$loUser = User::model()->findByAttributes(array('GUID'=>Yii::app()->user->GUID));

					$loAuthUser = new OAuthUser();
					$loAuthUser->Provider='Twitter';
					$loAuthUser->UserID=$loUser->UserID;
					$loAuthUser->UserGUID=$loUser->GUID;
					$loAuthUser->UID=$loUserInfo->id;
					$loAuthUser->DisplayName=$loUserInfo->screen_name;
					$loAuthUser->UserName=$loUserInfo->name;
				}
			}
			return $loAuthUser;
		}

        public static function getFacebookUser($toOAuthObject = NULL)
        {
            // TODO: Remove this after all twitter functionality has been refactored
            Yii::import('YIIPlinth.modules.UserManagement.models.*');
            Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.components.*');
            Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.models.*');
            Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.modules.Facebook.components.*');

            // Update the user information
            $loAuthUser = OAuthUser::model()->findByAttributes(array('UserGUID'=>Yii::app()->user->GUID, 'Provider'=>'Facebook'));

            if (is_null($loAuthUser) && !is_null($toOAuthObject))
            {
                $loUserInfo = $toOAuthObject->get('account/verify_credentials');

                if (isset($loUserInfo->error))
                {
                    echo $loUserInfo->error;
                    $loUserInfo = null;
                }

                if ($loUserInfo != NULL)
                {
                    $loUser = User::model()->findByAttributes(array('GUID'=>Yii::app()->user->GUID));

                    $loAuthUser = new OAuthUser();
                    $loAuthUser->Provider='Facebook';
                    $loAuthUser->UserID=$loUser->UserID;
                    $loAuthUser->UserGUID=$loUser->GUID;
                    $loAuthUser->UID=$loUserInfo->id;
                    $loAuthUser->DisplayName=$loUserInfo->screen_name;
                    $loAuthUser->UserName=$loUserInfo->name;
                }
            }
            return $loAuthUser;
        }

		public static function tweet($tcMessage, $tcAuthToken)
		{
			$loTwitter = self::getTwitterObject();
		}

		/**
		* Gets the current URL
		**/
		public static function getURL()
		{
			return Yii::app()->createAbsoluteUrl(str_replace(
						Yii::app()->request->baseUrl, '',
						Yii::app()->request->requestURI));
		}

		/**
		 * Checks if the current url is the same as tcURL
		 * @param  String the url to compare
		 * @return boolean        true if $tcURL points to the currently processing page
		 */
		public static function isCurrentURL($tcURL)
		{
			return Yii::app()->request->requestURI === $tcURL || Yii::app()->createUrl(Yii::app()->getController()->getRoute()) === $tcURL;
		}

		// TODO: Refactor Bit.ly to separate class
		/**
		* Shortens the specified URL using bit.ly
		**/
		public static function shortenURL($tcURL, $tcLogin = NULL, $tcAppKey = NULL)
		{
			// TODO: Cache and return cached if it exists
			if ($tcLogin === NULL)
			{
				$tcLogin = Yii::app()->params['bit.ly']['login'];
			}
			if ($tcAppKey === NULL)
			{
				$tcAppKey = Yii::app()->params['bit.ly']['key'];
			}
			if (!Utilities::endsWith($tcURL, '/'))
			{
				$tcURL.='/';
			}
			if (Utilities::startsWith($tcURL, '/'))
			{
				$tcURL = substr($tcURL, 1);
			}
			if (!Utilities::startsWith($tcURL, 'http'))
			{
				$tcURL = Yii::app()->getBaseUrl(true).$tcURL;
			}
			$tcURL = 'http://api.bit.ly/v3/shorten?login='.$tcLogin.'&apiKey='.$tcAppKey.'&format=txt&longUrl='.urlencode($tcURL);
			return @trim(file_get_contents($tcURL));
		}

		
	}

?>