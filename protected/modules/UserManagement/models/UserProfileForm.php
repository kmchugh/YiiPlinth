<?php

class UserProfileForm extends CFormModel
{
	public $email;
	public $displayName;
	public $country;
	public $profileImageURI;
	public $description;
	public $notifyUpdates;
	public $firstName;
	public $lastName;

	//public $fbUserName;
	//public $twitterUserName;

	private $m_oUser;
	private $m_oUserInfo;

	public static function load($tcGUID)
	{
		// Loads the model from the specified user id
		$loReturn = new UserProfile;

		$loReturn->setUser(User::model()->findByAttributes(array('GUID' => $tcGUID)));

		
		// The user model is required
		return $loReturn->m_oUser === null ? null : $loReturn;
	}

	public function getUser()
	{
		return $this->m_oUser;
	}

	private function setUser($toUser)
	{
		if ($toUser !== null)
		{
			$this->m_oUser = $toUser;

			$this->email = $this->m_oUser->Email;
			$this->displayName = $this->m_oUser->DisplayName;

			// Update the user info if needed
			if ($this->m_oUserInfo === null || $this->m_oUserInfo->UserID !== $this->m_oUser->UserID)
			{
				$this->m_oUserInfo = UserInfo::model()->findByAttributes(array('UserID' => $this->m_oUser->UserID));
				if ($this->m_oUserInfo !== null)
				{
					$this->country = $this->m_oUserInfo->Country;
					$this->profileImageURI = $this->m_oUserInfo->ProfileImageURI;
					$this->description = $this->m_oUserInfo->Description;
					$this->notifyUpdates = $this->m_oUserInfo->NotifyUpdates;
					$this->firstName = $this->m_oUserInfo->FirstName;
					$this->lastName = $this->m_oUserInfo->LastName;

					if ($this->country === null || $this->country === '')
					{
						$this->country = $this->getDefaultCountry();
					}
				}
				else
				{
					$this->m_oUserInfo = UserInfo::create($toUser);
				}
			}
		}
		else
		{
			// Clear the values
			$this->m_oUser = null;
			$this->m_oUserInfo = null;
			$this->email = null;
			$this->displayName = null;
			$this->country = null;
			$this->profileImageURI = null;
			$this->description = null;
		}
	}

	public function save()
	{
		if ($this->validate())
		{
			$this->m_oUser->save();
			
			if ($this->profileImageURI instanceof CUploadedFile)
			{
				$lcProfileImage = Yii::app()->params['directories']['profile'].'/'.$this->m_oUser->GUID.'/profile.'.$this->profileImageURI->getExtensionName();
				if (!is_dir(dirname($lcProfileImage))) 
				{
					mkdir(dirname($lcProfileImage), 0777, true);
				}

				if (is_file($lcProfileImage))
				{
					unlink($lcProfileImage);
				}

				if ($this->profileImageURI->saveAs($lcProfileImage, true))
				{
					$this->m_oUserInfo->ProfileImageURI = '/profiles/'.$this->m_oUser->GUID.'/profile.'.$this->profileImageURI->getExtensionName();

					$loImage = Yii::app()->image->load($lcProfileImage);
					$loImage->resize(256, 256);
					$loImage->save();
				}
				else
				{
					$this->m_oUserInfo->ProfileImageURI = '/images/profiles/defaultProfile.png';
				}
				$this->profileImageURI = $this->m_oUserInfo->ProfileImageURI;
			}

			$this->m_oUserInfo->save();

			if (Yii::app()->user->name === $this->m_oUser->Email)
			{
				Yii::app()->user->setState('DisplayName', $this->m_oUser->DisplayName);
			}
		}
		return false;
	}

	public function validate()
	{
		if (parent::validate() && $this->m_oUser !== null && $this->m_oUserInfo !== null)
		{
			// Update the User and validate
			$this->m_oUser->setAttributes(array(
				'DisplayName' => $this->displayName,
				'Email' => $this->email
				));

			$llReturn = $this->m_oUser->validate();

			// Update the Info and validate
			$this->m_oUserInfo->setAttributes(array(
				'Description' => $this->description,
				'Country' => $this->country,
				'ProfileImageURI' => $this->profileImageURI
				));

			return $llReturn && $this->m_oUserInfo->validate();
		}
		return false;
	}

	protected function beforeValidate()
	{
		if (is_null($this->profileImageURI))
		{
			$this->profileImageURI = $this->m_oUserInfo->ProfileImageURI;
		}
		return parent::beforeValidate();
	}

	private function getDefaultCountry()
	{
		$lnIPAddress = Yii::app()->request->userHostAddress;

    	if ($lnIPAddress === '')
    	{
    		$lnIPAddress = 0;
    	}
    	else
    	{
    		$lnIPAddress = explode('.', $lnIPAddress);
    		$lnIPAddress = ($lnIPAddress[3] + $lnIPAddress[2] * 256 +
    			$lnIPAddress[1] * 256 * 256 +
    			$lnIPAddress[0] * 256 * 256 * 256);
    	}

		$loCountry = CountryIP::model()->find(array(
			'condition'=>':ipAddress BETWEEN StartIP AND EndIP',
			'params'=>array(':ipAddress'=>$lnIPAddress),
			));
		return $loCountry !== null ? $loCountry->Country : null;
	}


	public function rules()
	{
		return array(
			array('displayName', 'required'),
			array('notifyUpdates', 'boolean'),
			array('email, country, firstName, lastName', 'length', 'max'=>255),
			array('displayName', 'length', 'max'=>150),
			array('description', 'safe'),
			array('profileImageURI', 'file', 
					'types'=>'png, gif, jpg, jpeg', 
					'maxSize'=>1024 * 200,
					'allowEmpty' => true),
			array('email', 'email'),
			array('email, displayName, country', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'email' => 'Email Address',
			'displayName' => 'Display Name',
			'country' => 'Country',
			'profileImageURI' => 'Profile Image',
			'description' => 'Description',
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'notifyUpdate' => 'Keep up to Date',
		);
	}

	/**
	 * Updates the user information
	 * @return boolean whether login is successful
	 */
	public function update()
	{
		return true;
	}
}
?>