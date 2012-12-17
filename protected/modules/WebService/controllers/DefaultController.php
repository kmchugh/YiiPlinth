<?php
/**
 * The Web Service default controller handles all of the actions for the WebService functionallity
 */
class DefaultController extends PlinthController
{
	private $format = 'json';
	private $limit = 50;

	private function createCriteria()
	{
		$loCriteria = new CDBCriteria();
		$loCriteria->limit = $this->limit;
		return $loCriteria;
	}

	public function actionIndex()
	{
		$this->missingAction('default');
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
			// There was no model
			echo "No MODEL";

		}
		/*
		Utilities::printVar($this->getModule()->configuration);
		echo $this->id;
		if($this->isDefault())
		{
			// Default request
			echo "default";
		}
		else if ($this->isModel())
		{
			// Model request
			echo "model";
		}
		else
		{
			// Invalid request
			echo "invalid";

		}
		*/
	}

	private function processModel($toModelInfo, $tcActionID, $tcMethod)
	{
		if (strcasecmp($tcMethod, 'GET') == 0)
		{

			// Get with no action should retrieve a list
			$loModel = $toModelInfo['class']::model()->findAll($this->createCriteria());
			$this->sendResponse(200, $loModel);
		}
		else
		{
			echo "unknown method";
		}
	}

	private function sendResponse($tnStatus = 200, $toContent=NULL, $tcContentType='application/json', $taMessages = NULL, $tlCaseInsensitive=true)
	{
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
		if (is_array($toContent))
		{
			$laResult['size']=count($toContent);
		}
		$laResult['result'] = $toContent;

		// If there are any errors, list them out
		if ($tnStatus != 200)
		{
			// TODO: Get the list of errors and render them
		}

		// If there is a response message, add it in
		if (!is_null($taMessages))
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

		@Yii::app()->end();
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

	public function actionView()
	{
		if (isset($_GET['model']) && (isset($_GET['id']) || isset($_GET['guid'])))
		{
			$lcModelName = $_GET['model'];
			$loModel = NULL;
			if (@class_exists($lcModelName, TRUE))
			{
				$loModel = isset($_GET['id']) ?
					$lcModelName::model()->findByPk($_GET['id']) :
					$lcModelName::model()->findByAttributes(array('GUID' => $_GET['guid']));
			}
			else
			{
				$lcID = isset($_GET['id']) ? $_GET['id'] : $_GET['guid'];
				$lcModelName = strtolower($lcModelName);
				if ($lcModelName === 'streamlistenerlist')
				{
					$loModel = Yii::app()->db->createCommand()
							->select('COUNT(DISTINCT `ListenerGUID`) as ListenerCount')
							->from('StreamListener')
							->join('Stream', 'Stream.StreamID = StreamListener.StreamID')
							->where('Stream.GUID=:streamGUID', array(':streamGUID'=>$lcID))
							->queryRow();
					$loModel = $loModel['ListenerCount'];
				}
				else if ($lcModelName === 'commentatorlistenerlist')
				{
					$loModel = Yii::app()->db->createCommand()
							->select('COUNT(DISTINCT `ListenerGUID`) as ListenerCount')
							->from('StreamListener')
							->join('StreamCommentator', 'StreamCommentator.StreamID = StreamListener.StreamID')
							->where('StreamCommentator.CommentatorGUID=:streamGUID', array(':streamGUID'=>$lcID))
							->queryRow();
					$loModel = $loModel['ListenerCount'];
				}
				else
				{
					$loModel = Yii::app()->db->createCommand()
							->select('*')
							->from('v'.$lcModelName)
							->queryAll();
				}
				
			}
			$this->sendResponse(200, $loModel, 'application/json');
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