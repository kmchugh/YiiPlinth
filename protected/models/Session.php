<?php

/**
 * This is the model class for table "Session".
 *
 * The followings are the available columns in table 'Session':
 * @property string $SessionID
 * @property string $GUID
 * @property string $IPAddress
 * @property string $Data
 * @property string $UserAgent
 * @property string $UserID
 * @property string $Expires
 * @property string $CreatedDate
 * @property string $CreatedBy
 * @property string $ModifiedDate
 * @property string $ModifiedBy
 * @property string $Rowversion
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Session extends PlinthModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Session the static model class
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
		return 'Session';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('GUID, IPAddress, UserAgent, Expires', 'required'),
			array('GUID, IPAddress', 'length', 'max'=>40),
			array('UserAgent', 'length', 'max'=>512),
			array('Data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('GUID, IPAddress, Data, UserAgent, UserID, Expires', 'safe', 'on'=>'search'),
		);
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
			'SessionID' => 'Session',
			'GUID' => 'GUID',
			'IPAddress' => 'IP Address',
			'Data' => 'Data',
			'UserAgent' => 'User Agent',
			'UserID' => 'User',
			'Expires' => 'Expires',
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

		$criteria->compare('GUID',$this->GUID,true);
		$criteria->compare('IPAddress',$this->IPAddress,true);
		$criteria->compare('Data',$this->Data,true);
		$criteria->compare('UserAgent',$this->UserAgent,true);
		$criteria->compare('UserID',$this->UserID,true);
		$criteria->compare('Expires',$this->Expires,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	protected function beforeValidate()
	{
		$this->IPAddress = Yii::app()->request->getUserHostAddress();
		$this->UserAgent = Yii::app()->request->getUserAgent();

		$loUser = Yii::app()->user;


		$this->UserID = !is_null($loUser) ? $loUser->id : NULL;

		return parent::beforeValidate();
	}
}