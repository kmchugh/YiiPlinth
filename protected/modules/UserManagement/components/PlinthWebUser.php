<?php
class PlinthWebUser extends CWebUser
{
	public $defaultProfileImageURI = '/images/icons/user_24x24.png';
	private $m_oUser;

	/**
	 * @param mixed $value the unique identifier for the user. If null, it means the user is a guest.
	 */
	public function setId($tnValue)
	{
		$this->m_oUser = NULL;
		$this->setState('__id',$tnValue);
	}

	/**
	 * Initializes the application component.
	 * This method overrides the parent implementation by starting session,
	 * performing cookie-based authentication if enabled, and updating the flash variables.
	 */
	public function init()
	{
		if (method_exists(Yii::app(), 'getSession'))
		{
			if($this->getIsGuest() && $this->allowAutoLogin)
				$this->restoreFromCookie();
			else if($this->autoRenewCookie && $this->allowAutoLogin)
				$this->renewCookie();
			if($this->autoUpdateFlash)
				$this->updateFlash();

			$this->updateAuthStatus();
		}
	}

	/**
	 * Returns the unique identifier for the user (e.g. username).
	 * This is the unique identifier that is mainly used for display purpose.
	 * @return string the user name. If the user is not logged in, this will be {@link guestName}.
	 */
	public function getName()
	{
		$this->getUser();
		return is_null($this->m_oUser) ? $this->guestName : $this->m_oUser->Email;
	}

	/**
	 * Gets the profile data for the user,.
	 * @return UserProfile the user profile data for the user, or null if it does not exist
	 */
	public function getProfile()
	{
		$this->getUser();
		return is_null($this->m_oUser) ? null : $this->m_oUser->profile;
	}

	/**
	 * Gets the profile image for the user, this will return defaultProfileImageURI if there is no user profile or no image
	 * @return string the uri to use as the users profile image
	 */
	public function getProfileImageURI()
	{
		$this->getUser();
		return Utilities::ISNULL(is_null($this->m_oUser) || is_null($this->m_oUser->profile) ? null : $this->m_oUser->profile->ProfileImageURI, $this->defaultProfileImageURI);
	}

	/**
	 * Gets the name of the user that is display safe
	 * @return string the display name of the current user
	 */
	public function getDisplayName()
	{
		$this->getUser();
		return is_null($this->m_oUser) ? $this->guestName : $this->m_oUser->DisplayName;
	}

	/**
	 * Gets the GUID of the current user, or null if the user is a guest
	 * @return string the user guid or null
	 */
	public function getGUID()
	{
		$this->getUser();
		return is_null($this->m_oUser) ? NULL : $this->m_oUser->GUID;
	}

	/**
	 * Gets the User object for user, this is a lazy load, and will only load the record if it has not already been loaded
	 * @return string gets the user object from the data source
	 */
	private function getUser()
	{
		if (is_null($this->m_oUser))
		{
			// TODO: May want to cache this object for the session
			$lnValue = $this->getState('__id');
			$this->m_oUser = is_null($lnValue) ? NULL :
				User::model()->findByAttributes(array('UserID'=>$lnValue));
		}
		return $this->m_oUser;
	}
}
?>