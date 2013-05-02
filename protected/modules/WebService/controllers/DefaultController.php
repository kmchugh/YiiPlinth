<?php
/**
 * The Web Service default controller handles all of the actions for the WebService functionallity
 */
class DefaultController extends PlinthController
{
	// TODO : Implement caching
	public $defaultLimit = 50;
	public $defaultCacheExpiry = 30000;

	private function getPrimaryKey($toModelInfo, $toModel)
	{
		return isset($toModelInfo['primaryKey']) ?
			$toModelInfo['primaryKey'] :
			$toModel->getMetaData()->tableSchema->primaryKey;
	}

	private function getUniqueKey($toModelInfo, $toModel)
	{
		return isset($toModelInfo['uniqueKey']) ? $toModelInfo['uniqueKey'] : 'GUID';
	}

	private function addWhere(&$taQuery, $taClause)
	{
		$lcWhere = isset($taQuery['where']) ? $taQuery['where'] : '';
		$laParams = isset($taQuery['params']) ? $taQuery['params'] : array();
		foreach ($taClause as $lcField => $loValue)
		{
			$lcReplacement = ':'.str_replace('.', '_', $lcField);
			$lcWhere.= (strlen($lcWhere) > 0 ? ' AND ' : '').$lcField.' = '.$lcReplacement;
			$laParams[$lcReplacement] = $loValue;
		}

		$taQuery['where']=$lcWhere;
		$taQuery['params']=$laParams;
		return $taQuery;
	}

	private function createQuery($toModelInfo, $toModel, $toUniqueIdentifier)
	{
		$loReturn = array(
			'from'=>isset($toModelInfo['from']) ? $toModelInfo['from'] : '{{'.$toModelInfo['class']::model()->tableName().'}}',
			'select'=>isset($toModelInfo['select']) ? $toModelInfo['select'] : '*',
			'limit'=>isset($toModelInfo['limit']) ? $toModelInfo['limit'] : $this->defaultLimit,
			'join'=>isset($toModelInfo['join']) ? $toModelInfo['join'] : '',
			);
		if (isset($toModelInfo['where']))
		{
			$this->addWhere($loReturn, $toModelInfo['where']);
		}
		if (isset($toModelInfo['order']))
		{
			$loReturn['order']=$toModelInfo['order'];
		}

		if (!is_null($toUniqueIdentifier))
		{
			if (is_numeric($toUniqueIdentifier))
			{
				$this->addWhere($loReturn, array($this->getPrimaryKey($toModelInfo, $toModel)=>$toUniqueIdentifier));
			}
			else
			{
				$this->addWhere($loReturn, array($this->getUniqueKey($toModelInfo, $toModel)=>$toUniqueIdentifier));
			}
		}

		$laParameters = Utilities::aSplit('&', '=', $_SERVER['QUERY_STRING']);
		foreach ($laParameters as $lcKey => $lcValue)
		{
			$this->addWhere($loReturn, array($lcKey=>$lcValue));
		}
		return $loReturn;
	}

	public function actionIndex()
	{
		$this->missingAction(NULL);
	}

	public function missingAction($tcActionID)
	{
		$loModel = $this->getModule()->getModelInfo($this->id);
		if (!is_null($loModel))
		{
			$this->processModel($loModel, $tcActionID, $_SERVER['REQUEST_METHOD']);
		}
		else
		{
			if (strcasecmp($this->id, 'default') ==0)
			{
				$this->sendResponse($this->getModule()->getModelList());
			}
			else
			{
				// There was no model
				$this->sendResponse(NULL, array($this->id.' is an invalid model.'), 404);
			}
		}
	}

	private function executeCommandFor($taQuery, &$taMessages)
	{
		$loReturn = null;
		$loCommand = Yii::app()->db->createCommand($taQuery);
		try
		{
			$loReturn = $loCommand->queryAll();
		}
		catch (CDbException $ex)
		{
			$lnReturnCode = 400;
			$taMessages[] = 'Invalid Query, check your parameters';

			if (Utilities::isDevelopment())
			{
				$taMessages[]=$ex->errorInfo[2];
			}
		}
		catch (Exception $ex)
		{
			if (Utilities::isDevelopment())
			{
				$taMessages[]=$ex->errorInfo[2];
			}
			$loReturn = NULL;
		}
		return $loReturn;
	}

	public function processModel($toModelInfo, $tcActionID, $tcMethod, $tlReturn = FALSE)
	{
		$laMessages = array();
		$lnReturnCode = 200;
		$laQuery = NULL;
		$lcMethod = strtolower($tcMethod);
		$lcCacheKey = strtolower($_SERVER['REQUEST_URI']);
		$laData = array();
		$loModel = $toModelInfo['class']::model();
		$loResponse = NULL;

		// TODO: Extract XML data as well as json
		if (strpos(strtolower($toModelInfo['options']), $lcMethod)!==false)
		{
			switch($lcMethod)
			{
				case 'get':
					$laData = $_GET;
					break;
				case 'post':
					$laData = CJSON::decode(isset($_POST['json']) ?
						$_POST['json'] : file_get_contents('php://input'), true);
					break;
                case 'put':
                    $laData = CJSON::decode(str_replace("json=", "", rawurldecode(file_get_contents('php://input'))), true);
                    break;
				default:
					$lnReturnCode=405;
			}
		}
		else
		{
			$lnReturnCode = 405;
		}

		// We are still okay so process
		if ($lnReturnCode == 200)
		{
			switch($lcMethod)
			{
				// Retrieve the list
				case 'get':
					if (isset($toModelInfo['cache']))
					{
						$loResponse = Utilities::ISNULLOREMPTY(Yii::app()->cache->get($lcCacheKey), NULL);
						// TODO: Send an expires header for cached
					}
					if (is_null($loResponse))
					{
						$laQuery = $this->createQuery($toModelInfo, $loModel, $tcActionID);
						$loResponse = $this->executeCommandFor($laQuery, $laMessages);

						if ($lnReturnCode == 200 && is_null($loResponse) || (is_array($loResponse) && count($loResponse) == 0))
						{
							$lnReturnCode = 404;
						}
						if (isset($toModelInfo['cache']) && $lnReturnCode >= 200 && $lnReturnCode < 400)
						{
							Yii::app()->cache->set($lcCacheKey, $loResponse, (isset($toModelInfo['cache']['expiry'])?$toModelInfo['cache']['expiry'] : $this->defaultCacheExpiry));
						}
					}
					break;

				// Create a new record
				case 'post':
					if (!isset($toModelInfo['onCreate']))
					{
						$loResponse = $this->createModel($this, $toModelInfo, $loModel, $laData, $lnReturnCode, $laMessages);
					}
					else
					{
						$loFunction = $toModelInfo['onCreate'];
						$loResponse = $loFunction($this, $toModelInfo, $loModel, $laData, $lnReturnCode, $laMessages);
					}
					break;

                // Update an existing record
                case 'put':
                    if (!isset($toModelInfo['onUpdate']))
                    {
                        $loResponse = $this->updateModel($this, $toModelInfo, $loModel, $laData, $tcActionID, $lnReturnCode, $laMessages);
                    }
                    else
                    {
                        $loFunction = $toModelInfo['onUpdate'];
                        $loResponse = $loFunction($this, $toModelInfo, $loModel, $laData, $tcActionID, $lnReturnCode, $laMessages);
                    }
                    break;

				default:
					$lnReturnCode=405;
			}
		}
		if ($tlReturn === false)
		{
			$this->sendResponse($loResponse, $laMessages, $lnReturnCode);
		}
		else
		{
			return array(
				'response'=>$loResponse,
				'messages'=>$laMessages,
				'resultCode'=>$lnReturnCode,
				);
		}
	}

    // TODO: Complete the automatic PUT request implementation
    private function updateModel($toController, $toModelInfo, $toModel, $taValues, $tcUniqueIdentifier, &$tnResult, &$taMessages)
    {
        $loInstance = new $toModel();
        $loInstance->setAttributes($taValues, false, true);



        // ModelID and URL ID Should be the same
        if (is_numeric($tcUniqueIdentifier))
        {
            if ($loInstance->getPrimaryKey() != intval($tcUniqueIdentifier))
            {
                $tnResult = 417;
                $taMessages[] = 'Unique Keys do not match ('.$loInstance->getPrimaryKey().' - '.$tcUniqueIdentifier.') '.get_class($toModel);
                return;
            }
        }
        else
        {
            if ($loInstance->getAttribute($this->getUniqueKey($toModelInfo, $toModel)) != intval($tcUniqueIdentifier))
            {
                // Probably a foreign key constraint failure, but we don't want to make that public
                $tnResult = 417;
                $taMessages[] = 'Unique Keys do not match ('.$loInstance->getAttribute($this->getUniqueKey($toModelInfo, $toModel)).' - '.$tcUniqueIdentifier.') '.get_class($toModel);
                return;
            }
        }

        $loInstance = $toModel->findByPk($loInstance->getPrimaryKey());
        $loInstance->setAttributes($taValues, false, true);

        try
        {
            if (!$loInstance->save())
            {
                foreach ($loInstance->getErrors() as $loMessage)
                {
                    $taMessages[] = $loMessage;
                }
                $tnResult = 417;
            }
            else
            {
                $loInstance = $toModel->findByAttributes(array('Rowversion'=>$loInstance->Rowversion));
                $laQuery = $this->createQuery($toModelInfo, $toModel, $loInstance->getPrimaryKey());

                $loResponse = $this->executeCommandFor($laQuery, $taMessages);
                $tnResult = 200;
                return $loResponse;
            }
        }
        catch (CDbException $ex)
        {
            // Probably a foreign key constraint failure, but we don't want to make that public
            $tnResult = 417;
            $taMessages[] = 'Unable to update existing '.get_class($toModel);
            if (Utilities::isDevelopment())
            {
                $taMessages[]=$ex->errorInfo[2];
            }
        }
    }

	private function createModel($toController, $toModelInfo, $toModel, $taValues, &$tnResult, &$taMessages)
	{
		$loInstance = new $toModel();
		$loInstance->setAttributes($taValues, false, true);

		try
		{
			if (!$loInstance->save())
			{
				foreach ($loInstance->getErrors() as $loMessage)
				{
					$taMessages[] = $loMessage;
				}
				$tnResult = 417;
			}
			else
			{
                $loInstance = $toModel->findByAttributes(array('Rowversion'=>$loInstance->Rowversion));
				$laQuery = $this->createQuery($toModelInfo, $toModel, $loInstance->getPrimaryKey());

				$loResponse = $this->executeCommandFor($laQuery, $taMessages);
				$tnResult = 201;
				return $loResponse;
			}
		}
		catch (CDbException $ex)
		{
			// Probably a foreign key constraint failure, but we don't want to make that public
			$tnResult = 417;
			$taMessages[] = 'Unable to create a new '.get_class($toModel);
			if (Utilities::isDevelopment())
			{
				$taMessages[]=$ex->errorInfo[2];
			}
		}
	}

	public function sendResponse($toContent=NULL, $taMessages = NULL, $tnStatus = 200, $tlCaseInsensitive=true)
	{
		//$tcContentType = (strpos($_SERVER['HTTP_ACCEPT'], 'json')) ? 'application/json' : 'text/html';
		$tcContentType = 'application/json';
		$lcStatusHeader = 'HTTP/1.1 '.$tnStatus.' '.$this->getStatusCodeMessage($tnStatus);
		header($lcStatusHeader);
		header('Content-type: '.$tcContentType);

		if ($toContent == NULL)
		{
			$toContent = array();
		}

		$laResult = array(
			'resultCode' => $tnStatus,
			'resultDescription' => $this->getStatusCodeMessage($tnStatus),
			);
		$lnCount = is_array($toContent) ? count($toContent) : 0;
		if ($lnCount > 0)
		{
			$laResult['count']=count($toContent);
		}
		$laResult['result'] = $toContent;

		// If there are any errors, list them out
		if ($tnStatus < 200 || $tnStatus >= 400)
		{
			// TODO: Get the list of errors and render them
		}

		// If there is a response message, add it in
		if (!is_null($taMessages) && count($taMessages) > 0)
		{
			$laResult['message'] = $taMessages;
		}

		if ($tlCaseInsensitive)
		{
			$laResult = Utilities::array_change_key_case_recursive($laResult);
		}

		// Respond
		echo !isset($_REQUEST['jsoncallback']) ?
			CJSON::encode($laResult) :
			$_REQUEST['jsoncallback'].'('.CJSON::encode($laResult).');';

		//@Yii::app()->end();
	}

	private function getStatusCodeMessage($tnStatus)
	{
		$laCodes = Array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
			);

		return (isset($laCodes[$tnStatus])) ? $laCodes[$tnStatus] : '';
	}

/*
	public function filters()
	{
		return array();
	}

	public function actionList()
	{
		if (isset($_GET['model']))
		{
			$lcModelName = $_GET['model'];
			$laRows = array();
			if (@class_exists($lcModelName, TRUE))
			{
				$loModels = $lcModelName::model()->findAll();
				foreach($loModels as $loModel)
				{
					$laRows[] = $loModel->attributes;
				}
			}
			else
			{
				$lcModelName = strtolower($lcModelName);
				if ($lcModelName === 'sessioninformation')
				{
					$laRows = Yii::app()->db->createCommand()
							->select('*')
							->from('v'.$lcModelName)
							->where('GUID=:sessionID', array(':sessionID' => Yii::app()->session->sessionID))
							->queryRow();

					if ($laRows != null)
					{
						// This is treated differently for now, need to update the android app to remove this
						$laInfo = array();
						$laInfo['GUID'] = $laRows['GUID'];
						$laInfo['displayName'] = $laRows['DisplayName'];
						$laInfo['isAuthenticated'] = $laRows['isAuthenticated'] == 1;
						$laInfo['profileURL'] = 'http://' . $_SERVER['HTTP_HOST'].(is_null($laRows['profileURL']) ?
								'/images/profiles/defaultProfile.png' :
								$laRows['profileURL']);

						$laRows = $laInfo;
						$this->sendResponse(200, $laRows, 'application/json', null, false);
						return;
					}
				}
				else
				{
					$lcID = 'WS_DATA_'.'v'.$lcModelName;
					$laRows = Yii::app()->cache->get($lcID);
					if ($laRows===false)
					{
						$laRows = Yii::app()->db->createCommand()
							->select('*')
							->from('v'.$lcModelName)
							->queryAll();
						Yii::app()->cache->set($lcID, $laRows, 10);
					}
				}
			}
			$this->sendResponse(200, $laRows, 'application/json');
		}
	}

	public function actionCreate()
	{
		$loJSON = CJSON::decode(isset($_POST['json']) ? $_POST['json'] : file_get_contents("php://input"), true);
		foreach ($loJSON as $lcKey => $loValue) 
		{
			$_POST[$lcKey] = $loValue;
		}

		if (isset($_REQUEST['model']))
		{
			$lcModelName = $_REQUEST['model'];
			$loModel = NULL;

			$loUser = Yii::app()->user;
			$lcUser = Yii::app()->user->GUID;

			$lcModelName = strtolower($lcModelName);
			if ($lcModelName === 'stream')
			{
				// Create a new stream
				$lcCommentatorGUID = is_null($lcUser) ? 'SYSTEM' : $lcUser;
				$lcEventGUID = isset($_POST['eventguid']) ? $_POST['eventguid'] : NULL;
				$loEvent = !is_null($lcEventGUID) ? Event::model()->findByAttributes(array('GUID' => $lcEventGUID)) : NULL;

				if ($loEvent == NULL)
				{
					// Create a new event
					$loEvent = new Event;
					$loEvent->setAttributes(array(
						'Title' => $_POST['title'],
						'Keywords' => $_POST['keywords'],
						'StartDate' => $_POST['startdate']
						));
					if (!$loEvent->save())
					{
						$this->sendResponse(500, NULL, 'application/json');
					}
				}

				// Create a new Stream
				$loStream = new Stream;
				$loStream->setAttributes(array(
					'EventID' => $loEvent->EventID,
					'StartDate' => $_POST['startdate'],
					'IsPrivate' => isset($_POST['isprivate']) ? $_POST['isprivate'] : false));

				if (!$loStream->save())
				{
					$this->sendResponse(500, NULL, 'application/json');
				}

				// Create the stream commentator
				$loStreamCommentator = new Streamcommentator;
				$loStreamCommentator->setAttributes(array(
					'EventID' => $loEvent->EventID,
					'StreamID' => $loStream->StreamID,
					'StartDate' => $_POST['startdate'],
					'isOwner' => TRUE,
					'CommentatorGUID' => $lcCommentatorGUID
					));
				if (!$loStreamCommentator->save())
				{
					$this->sendResponse(500, NULL, 'application/json');
				}

				// Everything was okay, return the stream information
				$loModel = Yii::app()->db->createCommand()
							->select('*')
							->from('vStreamInfo')
							->where('StreamGUID=:streamGUID', array(':streamGUID'=>$loStream->GUID))
							->queryRow();

				if (!$loStream->IsPrivate)
				{
					$loUserInfo = UserInfo::model()->findByAttributes(array('UserID'=>Yii::app()->user->id));
					$lcURL ='http://www.youcommentate.com/'.(is_null($loUserInfo) ? 'Stream/View/guid/'.$loStream->GUID : $loUserInfo->UserURL);
					// If the user has linked their Twitter account, TWEET!
					$lcTweet = "I'm live on @YouCommentate, check me out at ".Utilities::shortenURL($lcURL).' - '.$loEvent->Title;
					if (strlen($lcTweet) >= 140)
					{
						$lcTweet = substr($lcTweet, 0, 137).'...';
					}
					Utilities::sendTweet($lcTweet);
				}
			}
			else if ($lcModelName === 'streamlistener')
			{
			}
			else
			{
				// TODO: Deal with these generically, refactor the above conditionals
				if (@class_exists($lcModelName, TRUE))
				{
					$lcID = $_GET['id'];
					if (is_numeric($lcID))
					{
						// By ID
						
					}
					else
					{
						// By GUID
						
					}
				}
			}
			$this->sendResponse(200, $loModel, 'application/json');
		}
	}
	public function actionUpdate()
	{
		if (isset($_GET['model']) && (isset($_GET['id']) || isset($_GET['guid'])))
		{
			$lcModelName = $_GET['model'];
			$loModel = NULL;
			$lcID = isset($_GET['id']) ? $_GET['id'] : $_GET['guid'];
			$lcModelName = strtolower($lcModelName);
			if ($lcModelName === 'streamlistenerlist')
			{
				$loStream = Stream::model()->findByAttributes(array('GUID'=>$_GET['guid']));
				$loModel = Yii::app()->db->createCommand()
						->update('StreamListener',
								array('EndDate'=> Utilities::getTimestamp()),
								'EndDate IS NULL AND StreamID=:streamID AND ListenerGUID=:listenerGUID',
								array(':streamID'=>$loStream->StreamID,
									':listenerGUID'=>!Yii::app()->user->isGuest ? 
								Yii::app()->user->GUID: 
								Yii::app()->getSession()->sessionID));
			}
			else
			{
				$loModel = Yii::app()->db->createCommand()
						->select('*')
						->from('v'.$lcModelName)
						->queryAll();
			}
			$this->sendResponse(200, $loModel, 'application/json');
		}
		$this->sendResponse(500, NULL, 'application/json');
	}

	public function actionDelete()
	{
		
	}

	
	*/

}

?>