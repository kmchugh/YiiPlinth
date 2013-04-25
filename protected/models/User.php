<?php

/**
 * This is the model class for table "User".
 *
 * The followings are the available columns in table 'User':
 * @property string $UserID
 * @property string $GUID
 * @property string $Email
 * @property string $DisplayName
 * @property string $Slug
 * @property string $Password
 * @property boolean $Locked
 * @property string $StartDate
 * @property string $EndDate
 * @property string $LoginCount
 * @property string $LastLoginDate
 * @property string $CreatedDate
 * @property string $CreatedBy
 * @property string $ModifiedDate
 * @property string $ModifiedBy
 * @property string $Rowversion
 *
 * The followings are the available model relations:
 * @property UserInfo[] $profile
 * @property Membership[] $memberships
 * @property Session[] $sessions
 */
class User extends PlinthModel
{
    // TODO: Refactor this to PlinthModel
    public static function findBySlug($tcSlug)
    {
        return User::model()->findByAttributes(array('Slug'=>md5(strtolower($tcSlug))));
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public $slug = array('DisplayName');

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'User';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('Email, DisplayName', 'required'),
			array('Email', 'length', 'max'=>255),
			array('DisplayName', 'length', 'max'=>150),
			array('Password', 'length', 'max'=>32),
			array('Email, DisplayName', 'unique'),
			array('Email', 'email'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('DisplayName', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			// TODO: Remove the profile relation
			'profile' => array(self::HAS_ONE, 'UserInfo', 'UserID'),
			'memberships' => array(self::HAS_MANY, 'Membership', 'MemberUserID'),
			'sessions' => array(self::HAS_MANY, 'Session', 'UserID'),
			'userInfo' => array(self::HAS_ONE, 'UserInfo', 'UserID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'UserID' => 'User',
			'GUID' => 'Guid',
			'Email' => 'Email',
			'DisplayName' => 'Display Name',
			'Password' => 'Password',
			'Locked' => 'Locked',
			'StartDate' => 'Start Date',
			'EndDate' => 'End Date',
			'LoginCount' => 'Login Count',
			'LastLoginDate' => 'Last Login Date',
			'Anonymous' => 'Anonymous',
			'CreatedDate' => 'Created Date',
			'CreatedBy' => 'Created By',
			'ModifiedDate' => 'Modified Date',
			'ModifiedBy' => 'Modified By',
			'Rowversion' => 'Rowversion',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('DisplayName',$this->DisplayName,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	* Gets the encrypted value with the email seed
	* @var tcSalt the salt value for the password hash
	* @var the password
	* @return the hash of the password, NULL if $tcPassword is NULL
	**/
	private function getPasswordHash($tcPassword)
	{
		return $tcPassword === NULL ? NULL : md5(strtolower($this->Email).'|'.$tcPassword);
	}

	/**
	* Sets the users password to $tcNewPassword if $tcOldPassword matches.  If
	* this is a new user then NULL should be passed as $tcOldPassword.  This does 
	* NOT persist the change to the data store.  You must save the record to 
	* persist the change
	* @var tcPassword the current password
	* @var tcNewPassword the password to change to
	* @return true if the password was reset, otherwise false
	**/
	public function changePassword($tcPassword, $tcNewPassword)
	{
		if ($this->validatePassword($tcPassword))
		{
			$this->Password = $this->getPasswordHash($tcNewPassword);
			return true;
		}
		return false;
	}

	/**
	* Forces a change to the users password, this disregards the users
	* old password, or any authentication.  This does not save the record
	* to the data store.
	* @var tcPassword the password to change to
	**/
	public function resetPassword($tcPassword)
	{
		$this->Password = $this->getPasswordHash($tcPassword);
	}

	/**
	* Checks if $tcPassword matches the users current password.  A password IS case
	* sensitive
	* @return true if the password authenticates
	**/
	public function validatePassword($tcPassword)
	{
		$llReturn = $this->getPasswordHash($tcPassword) === $this->Password;
		if ($llReturn === true)
		{
			// Now that the user has been authenticated, update the login time
			$this->setAttributes(
				array(
					'LastLoginDate' => Utilities::getTimeStamp(),
    					'LoginCount' => $this->LoginCount +1,), false);
			$this->save();
		}
		return $llReturn;
	}

	/**
	 * Creates a new user entity from the email address provided.
	 * @param  string $tcEmail the email address to create the user from
	 * @return the newly created user, it is possible the user could not be saved so may be in an error state
	 */
	public static function create($tcEmail)
	{
		$lcPassword = substr(Utilities::getStringGUID(), 0, 10);
        $loUser = new User;
        $loUser->setAttributes(
            array(
                'Email' => $tcEmail,
                'DisplayName' => substr($tcEmail, 0, strpos($tcEmail, '@')),
                'StartDate' => Utilities::getTimeStamp(),
            ), false);
        $loUser->resetPassword($lcPassword);
        if ($loUser->save())
        {
            // Create a token to allow the user to set their own password
            // Create a new token
            $loToken = new ChangePasswordToken();
            $loToken->UserGUID = $loUser->GUID;
            if ($loToken->save())
            {
                // Send the user an email with a link to change password
                $loEmail = new YiiMailMessage;
                $loEmail->view = '//mail/userRegistration';
                $loEmail->layout = '//layouts/mail';
                $loEmail->setBody(array('title'=>Utilities::getString('User account created'), 'userModel'=>$loUser, 'resetURL'=>Yii::app()->createAbsoluteUrl('changePassword',array('token'=>$loToken->Token))), 'text/html');
                $loEmail->setSubject(Utilities::getString('registration_email_subject'));
                $loEmail->addTo($loUser->Email);
                $loEmail->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminName']));
                Yii::app()->mail->send($loEmail);
            }

            // Once a User record is created, create a User Profile
            $loUserInfo = UserInfo::create($loUser);
        }
        return $loUser;
	}
}