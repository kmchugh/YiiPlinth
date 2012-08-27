<?php
class DefaultController extends PlinthController
{
	private $format = 'json';

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
					$laRows = Yii::app()->db->createCommand()
							->select('*')
							->from('v'.$lcModelName)
							->queryAll();
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
			$lcUser = Yii::app()->user->getState('GUID');

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
					// If the user has linked their Twitter account, TWEET!
					$lcTweet = "I'm live on @YouCommentate, check me out at ".Utilities::shortenURL('http://www.youcommentate.com/Stream/View/guid/'.$loStream->GUID).' - '.$loEvent->Title;
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
								Yii::app()->user->getState('GUID') : 
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

	private function sendResponse($tnStatus = 200, $toContent=NULL, $tcContentType='text/html', $taMessages = NULL, $tlCaseInsensitive=true)
	{
		$lcStatusHeader = 'HTTP/1.1 '.$tnStatus.' '.$this->getStatusCodeMessage($tnStatus);
		header($lcStatusHeader);
		//header('Content-type: '.$tcContentType);

		if ($toContent == NULL)
		{
			$toContent = array();
		}

		$laResult = array(
			'resultCode' => $tnStatus,
			'resultDescription' => $this->getStatusCodeMessage($tnStatus),
			'result' => $toContent,
			);

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
			200 => 'OK',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			);

		return (isset($laCodes[$tnStatus])) ? $laCodes[$tnStatus] : '';
	}

}

?>