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

	public function __construct($tcUserName, $tcPassword)
	{
		$this->m_oUser = User::model()->findByAttributes(array('Email'=>$tcUserName));
		parent::__construct($tcUserName, $tcPassword);
	}

	public function authenticate()
	{
		if ($this->m_oUser===null)
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		else
		{
			echo $this->m_oUser->validatePassword($this->password);
			$this->errorCode = (!$this->m_oUser->validatePassword($this->password)) ? self::ERROR_PASSWORD_INVALID : self::ERROR_NONE;
		}
		echo $this->errorCode;
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