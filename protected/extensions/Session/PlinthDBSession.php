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
	* Craetes the session table if it does not already exist
	**/
	protected function createSessionTable($toDB, $tcTableName)
	{
	   $lcSQL="CREATE TABLE IF NOT EXISTS `{$tcTableName}` (
          `SessionID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  `GUID` varchar(40) NOT NULL,
		  `IPAddress` varchar(40) NOT NULL,
		  `Data` text,
		  `UserAgent` varchar(512) NOT NULL,
		  `UserID` bigint(20) unsigned DEFAULT NULL,
		  `Expires` bigint(20) unsigned NOT NULL,
		  `CreatedDate` bigint(20) unsigned NOT NULL,
		  `CreatedBy` varchar(40) NOT NULL,
		  `ModifiedDate` bigint(20) unsigned NOT NULL,
		  `ModifiedBy` varchar(40) NOT NULL,
		  `Rowversion` bigint(20) unsigned NOT NULL,
		  PRIMARY KEY (`SessionID`),
		  UNIQUE KEY `guid` (`GUID`),
		  KEY `FK_Session_UserID` (`UserID`),
		  CONSTRAINT `FK_Session_UserID` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`) ON DELETE NO ACTION ON UPDATE NO ACTION
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$toDB->createCommand($lcSQL)->execute();
	}

	/**
	* Reads the information about the session
	**/
	public function readSession($tcSessionID)
	{
		$loSession = Session::model()->findByAttributes(array('GUID' => $tcSessionID));

		return !is_null($loSession) ? $loSession->Data : '';
	}

	public function writeSession($tcSessionID, $tcData)
	{
		$loSession = Session::model()->findByAttributes(array('GUID' => $tcSessionID));
		if (is_null($loSession))
		{
			$loSession = new Session;
		}

		$loSession->GUID = $tcSessionID;
		$loSession->Data = $tcData;
		$loSession->Expires = Utilities::scientificToLong(Utilities::getTimeStamp() + (Yii::app()->user->isGuest ? $this->getTimeout() : 3600 * 24) * 1000);

		if (!$loSession->save())
		{
			if(YII_DEBUG)
				echo $e->getMessage();
			return false;
		}
		return true;
	}

	public function regenerateID($tlDeleteOldSession=false)
	{
		$loSession = Session::model()->findByAttributes(array('GUID' => session_id()));
		if (!is_null($loSession))
		{
			// SESSION ID is NOT changed when the user logs in
			//CHttpSession::regenerateID($tlDeleteOldSession);
			$loSession->GUID = session_id();
			if (!$tlDeleteOldSession)
			{
				//unset($loSession->SessionID);
				//$loSession->isNewRecord = true;
			}
			$loSession->save();
		}
	}

	public function openSession($tcSavePath,$tcSessionID)
	{
		if($this->autoCreateSessionTable)
		{
			$loDB=$this->getDbConnection();
			$loDB->setActive(true);
			$lcSQL="DELETE FROM {$this->sessionTableName} WHERE expires<".Utilities::getTimestamp();
			try
			{
				$loDB->createCommand($lcSQL)->execute();
			}
			catch(Exception $ex)
			{
				$this->createSessionTable($loDB,$this->sessionTableName);
			}
		}
		return true;
	}

	public function destroySession($tcSessionID)
	{
		$loSession = Session::model()->findByAttributes(array('GUID' => session_id()));

		// Sesssion ID is changed when the user logs out
		CHttpSession::regenerateID(false);
		return !is_null($loSession) ? $loSession->delete() : true;
	}

	public function gcSession($tnMaxLifetime)
	{
		Session::model()->deleteAll(
			'`expires` < :expiry',
			array(':expiry' => Utilities::getTimeStamp()));
		return true;
	}
}

?>