<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $m_cUserID;
	private $m_cUserGUID;
	private $m_cDisplayName;

	public function authenticate()
	{
		$loUser = User::model()->findByAttributes(array('Email'=>$this->username));

		if ($loUser===null)
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		else 
		{
			if ($loUser->Password !==Utilities::getPasswordValue($loUser->Email, $this->password))
			{
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
			}
			else
			{
				$this->m_cUserID = $loUser->UserID;
				$this->m_cUserGUID = $loUser->UserGUID;
				$this->m_cDisplayName = $loUser->DisplayName;

				$this->setState('lastLoginTime', (null===$loUser->LastLoginDate) ? 
					Utilities::getTimestamp() :
					$loUser->LastLoginDate);

				// Now that the user has been authenticated, update the login time
				$loUser->setAttributes(
					'LastLoginDate' => Utilities::getTimeStamp(),
    				'LoginCount' => $loUser->LoginCount +1,
					);
				$loUser->save();
	
				$this->setState('GUID', $loUser->GUID);
				$this->setState('DisplayName', $loUser->DisplayName);
				$this->errorCode=self::ERROR_NONE;
			}
		}
		return !$this->errorCode;
	}


	/**
	* Retrieves the users ID
	**/
	public function getId()
	{
		return $this->m_cUserID;
	}

	/**
	* Retrieves the users GUID
	**/
	public function getGUID()
	{
		return $this->m_cUserGUID;
	}

	/**
	* Retrieves the users Display Name
	**/
	public function getDisplayName()
	{
		return $this->m_cDisplayName;
	}
}