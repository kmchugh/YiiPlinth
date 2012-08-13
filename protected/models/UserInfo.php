<?php

/**
 * This is the model class for table "UserInfo".
 *
 * The followings are the available columns in table 'UserInfo':
 * @property string $UserInfoID
 * @property string $UserID
 * @property string $Country
 * @property integer $NotifyUpdates
 * @property string $ProfileImageURI
 * @property string $FirstName
 * @property string $LastName
 * @property string $Description
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
		$this->NotifyUpdates = ord($this->NotifyUpdates);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('UserID, CreatedDate, ModifiedDate, Rowversion', 'length', 'max'=>20),
			array('Country, FirstName, LastName', 'length', 'max'=>255),
			array('NotifyUpdates', 'boolean'),
			array('CreatedBy, ModifiedBy', 'length', 'max'=>40),
			array('ProfileImageURI', 'file', 
					'types'=>'png, gif, jpg, jpeg', 
					'maxSize'=>1024 * 200,
					'tooLarge' => 'The maximum file size should be 200kb',
					'wrongType' => 'Only .png, .gif, .jpg, and .jpeg are allowed',
					'allowEmpty' => true),
			array('Description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('UserInfoID, UserID, Country, ProfileImageURI, Description, CreatedDate, CreatedBy, ModifiedDate, ModifiedBy, Rowversion', 'safe', 'on'=>'search'),
		);
	}

	protected function beforeValidate()
	{
		// Purify the description
		$loPurify = new CHtmlPurifier();
		$this->Description = $loPurify->purify($this->Description);
		return parent::beforeValidate();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'UserID'),
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
			'Country' => 'Country',
			'ProfileImageURI' => 'Profile Image Uri',
			'Description' => 'Description',
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
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('UserInfoID',$this->UserInfoID,true);
		$criteria->compare('UserID',$this->UserID,true);
		$criteria->compare('Country',$this->Country,true);
		$criteria->compare('ProfileImageURI',$this->ProfileImageURI,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('CreatedDate',$this->CreatedDate,true);
		$criteria->compare('CreatedBy',$this->CreatedBy,true);
		$criteria->compare('ModifiedDate',$this->ModifiedDate,true);
		$criteria->compare('ModifiedBy',$this->ModifiedBy,true);
		$criteria->compare('Rowversion',$this->Rowversion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}