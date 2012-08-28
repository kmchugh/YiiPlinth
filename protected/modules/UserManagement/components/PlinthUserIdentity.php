<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class PlinthUserIdentity extends CUserIdentity
{
	private $m_cUserID;
	private $m_oUser;

	public function authenticate()
	{
		$this->m_oUser = User::model()->findByAttributes(array('Email'=>$this->username));
		if ($this->m_oUser===null)
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		else 
		{
			if (!$this->m_oUser->validatePassword($this->password))
			{
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
			}
			else
			{
				// Now that the user has been authenticated, update the login time
				$this->m_oUser->setAttributes(
					array(
						'LastLoginDate' => Utilities::getTimeStamp(),
	    					'LoginCount' => $this->m_oUser->LoginCount +1,), false);
				$this->m_oUser->save();
				$this->errorCode=self::ERROR_NONE;
			}
		}
		return !$this->errorCode;
	}


	/**
	* Retrieves the users ID
	* @return the user id
	**/
	public function getId()
	{
		return $this->m_oUser->UserID;
	}

	/**
	* Retrieves the users GUID
	* @return the user GUID
	**/
	public function getGUID()
	{
		return $this->m_oUser->GUID;
	}

	/**
	* Retrieves the users Display Name
	* @return the user display name
	**/
	public function getDisplayName()
	{
		return $this->m_oUser->DisplayName;
	}
}