<?php

/**
 * This is the model class for table "UserInfo".
 *
 * The followings are the available columns in table 'UserInfo':
 * @property string $UserInfoID
 * @property string $UserID
 * @property string $CountryID
 * @property string $ProfileImageURI
 * @property string $FirstName
 * @property string $LastName
 * @property string $Description
 * @property string $UserURL
 * @property string $BirthDay
 * @property string $BirthMonth
 * @property string $BirthYear
 * @property string $GenderID
 * @property integer $Featured
 * @property string $CreatedDate
 * @property string $CreatedBy
 * @property string $ModifiedDate
 * @property string $ModifiedBy
 * @property string $Rowversion
 *
 * The followings are the available model relations:
 * @property User $user
 */
class UserInfo extends PlinthModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'UserInfo';
	}

	public function afterFind()
	{
		parent::afterFind();
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('UserID, CreatedDate, ModifiedDate, Rowversion', 'length', 'max'=>20),
			array('FirstName, LastName', 'length', 'max'=>255),
			array('UserURL', 'length', 'max'=>40),
			array('UserURL', 'required'),
			array('ProfileImageURI', 'file', 
					'types'=>'png, gif, jpg, jpeg', 
					'maxSize'=>1024 * 200,
					'tooLarge' => 'The maximum file size should be 200kb',
					'wrongType' => 'Only .png, .gif, .jpg, and .jpeg are allowed',
					'allowEmpty' => true),
			array('Description', 'safe'),
			array('UserURL', 'unique'),
			array('Description', 'safe', 'on'=>'search'),
		);
	}

	protected function beforeValidate()
	{
		// Purify the description
		$loPurify = new CHtmlPurifier();
		$this->Description = $loPurify->purify($this->Description);

		// Clean up the User URL
		if ($this->UserURL === NULL || strlen($this->UserURL) === 0)
		{
			$this->UserURL = strtolower(preg_replace("/[^A-Za-z0-9_]/", '', $this->user->DisplayName));
		}

		// Set a profile URL if needed
		$laDefaultProfiles = Yii::app()->params['defaults']['profileImages'];
		if (count($laDefaultProfiles) > 0 && ($this->ProfileImageURI === NULL || strlen($this->ProfileImageURI) === 0))
		{
			$this->ProfileImageURI = $laDefaultProfiles[rand(0, count($laDefaultProfiles) -1)];
		}
		return parent::beforeValidate();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'UserID'),
			'country' => array(self::BELONGS_TO, 'Country', 'CountryID'),
			'gender' => array(self::BELONGS_TO, 'Gender', 'GenderID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'UserInfoID' => 'User Info',
			'UserID' => 'User',
			'CountryID' => 'Country',
			'ProfileImageURI' => 'Profile Image Uri',
			'Description' => 'Description',
			'UserURL' => 'User URL',
			'Featured' => 'Featured',
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

		$criteria->compare('Description',$this->Description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}