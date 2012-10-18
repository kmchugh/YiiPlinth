<?php
/**
* The PlinthDBSession class controlls the users sesssion.
* The session is defined by the value of the PHPSESSID, and 
* a record in the DB with the same GUID.  No information
* besides the Session ID is stored on the client side.
* The PHPSESSID will expire ~20 minutes from the last activity
* for guests and users who authenticated on the web site.
* For users who authenticate through their mobile app it will
* be 8 days from the last activity.
**/
class PlinthDBSession extends CDbHttpSession
{
	/**
	* Creates the session table if it does not already exist
	**/
	protected function createSessionTable()
	{
		$loDB = $this->getDbConnection();
		$lcTableName = $this->sessionTableName;
		$loCommand = $loDB->createCommand();
		$loCommand->createTable("{$lcTableName}",
			array(
				'SessionID'=>'pk',
				'GUID'=>'guid',
				'IPAddress'=>'string',
				'Data'=>'text',
				'UserAgent'=>'long_string',
				'UserID'=>'id_null',
				'Expires'=>'datetime',
				'CreatedDate'=>'datetime',
				'CreatedBy'=>'guid',
				'ModifiedDate'=>'datetime',
				'ModifiedBy'=>'guid',
				'Rowversion'=>'datetime',
				));

		$loCommand->addForeignKey('FK_'.$lcTableName.'_UserID',  "{$lcTableName}", 'UserID',
					"{{User}}", 'UserID', 'NO ACTION', 'NO ACTION');
	}

	/**
	* Initiaalises the application component
	**/
	public function init()
	{
		$this->setTimeout(1440);
		if (isset($_COOKIE[session_name()]))
		{
			setcookie(session_name(), $_COOKIE[session_name()], time()+$this->getTimeout(), '/');
		}
		parent::init();
	}

	/**
	* Session Read handler, called by php, do not call directly
	* @param string $tcSessionID the session ID to read
	* @return string the session data or an empty string if the session did not exist
	**/
	public function readSession($tcSessionID)
	{
		$loSession = null;
		try
		{
			$loSession = NULL;
			if (Utilities::entityExists($this->getDbConnection(), $this->sessionTableName))
			{
				// The session is being read which means it is active, so set the timeout appropriately
				$loSession = Session::model()->findByAttributes(array('GUID' => $tcSessionID));
				$loSession->Expires = Utilities::scientificToLong(Utilities::getTimestamp() + ($this->getTimeout() * 1000));
			}

		}
		catch(Exception $ex)
		{
			// This may occur if the table does not yet exist
		}
		return !is_null($loSession) ? $loSession->Data : '';
	}

	/**
	* Makes sure the Session Table exists, creates it if it does not exist
	**/
	private function ensureTableExists()
	{
		if ($this->autoCreateSessionTable && !Utilities::entityExists($this->getDbConnection(), $this->sessionTableName))
		{
			$this->createSessionTable($this->getDbConnection(),$this->sessionTableName);
		}
		return $this->autoCreateSessionTable;
	}

	/**
	* Session write handler, do not call this directly
	* @param string $tcSessionID the session ID of the session to write
	* @param string $tcData the Session data to write to the db
	**/
	public function writeSession($tcSessionID, $tcData)
	{
		// Make sure the table can be created if there is a problem
		if($this->ensureTableExists())
		{
			$loSession = Session::model()->findByAttributes(array('GUID' => $tcSessionID));

			if (is_null($loSession))
			{
				$loSession = new Session;
			}

			$loSession->GUID = $tcSessionID;
			$loSession->Data = $tcData;

			// Set the expiry time as a number of milliseconds
			$loSession->Expires = Utilities::scientificToLong(Utilities::getTimestamp() + ($this->getTimeout() * 1000));
			if (!$loSession->save())
			{
				if(YII_DEBUG)
				{
					echo $e->getMessage();
				}
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	* Forces the session id to change, this will overwrite the old session.
	* If there was no existing session, this is a no op.
	* @param $tlDeleteOldSession, this paramter is ignored
	**/
	public function regenerateID($tlDeleteOldSession=false)
	{
		// Retrieve the session by name
		$loSession = Session::model()->findByAttributes(array('GUID' => session_id()));
		if (!is_null($loSession))
		{
			$loSession->GUID = session_id();
			$loSession->save();
		}
	}

	/**
	* Session open handler, should not be called directly
	* @param $tcSavePath, parameter is ignored
	* @param string $tcSessionID, the session id to save
	**/
	public function openSession($tcSavePath,$tcSessionID)
	{
		if($this->autoCreateSessionTable)
		{
			try
			{
				Session::model()->deleteAll(
					'`expires` < :expiry',
					array(':expiry' => Utilities::getTimeStamp()));
			}
			catch(Exception $e)
			{
				$this->createSessionTable($db,$this->sessionTableName);
			}
		}
		return true;
	}

	/**
	* Session destroy handler, should not be called directly
	* @param string $tcSessionID the session to destroy
	**/
	public function destroySession($tcSessionID)
	{
		$loSession = Session::model()->findByAttributes(array('GUID' => $tcSessionID));
		// Destroying the session causes the current session id to change
		CHttpSession::regenerateID(false);
		return !is_null($loSession) ? $loSession->delete() : true;
	}

	/**
	* Session garbage collection handler, should not be called directly.
	* Deletes any sessions that have expired
	* @param integer $tnMaxLifetime this parameter is ignored
	**/
	public function gcSession($tnMaxLifetime)
	{
		Session::model()->deleteAll(
			'`expires` < :expiry',
			array(':expiry' => Utilities::getTimeStamp()));
		return true;
	}
}

?>