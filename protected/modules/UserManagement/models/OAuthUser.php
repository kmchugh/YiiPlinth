<?php

/**
 * This is the model class for table "OAuthUser".
 *
 * The followings are the available columns in table 'OAuthUser':
 * @property string $OAuthUserID
 * @property string $UserID
 * @property string $UserGUID
 * @property string $Provider
 * @property string $UID
 * @property string $Token
 * @property string $Secret
 * @property string $DisplayName
 * @property string $UserName
 * @property string $CreatedDate
 * @property string $CreatedBy
 * @property string $ModifiedDate
 * @property string $ModifiedBy
 * @property string $Rowversion
 *
 * The followings are the available model relations:
 * @property User $user
 */
class OAuthUser extends PlinthModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OAuthUser the static model class
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
		return 'OAuthUser';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Provider', 'length', 'max'=>10),
			array('UID, Token, Secret, DisplayName, UserName', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('OAuthUserID, UserID, UserGUID, Provider, UID, Token, Secret, DisplayName, UserName, CreatedDate, CreatedBy, ModifiedDate, ModifiedBy, Rowversion', 'safe', 'on'=>'search'),
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
			'OAuthUserID' => 'Oauth User',
			'UserID' => 'User',
			'UserGUID' => 'User Guid',
			'Provider' => 'Provider',
			'UID' => 'Uid',
			'Token' => 'Token',
			'Secret' => 'Secret',
			'DisplayName' => 'Display Name',
			'UserName' => 'User Name',
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

		$criteria->compare('OAuthUserID',$this->OAuthUserID,true);
		$criteria->compare('UserID',$this->UserID,true);
		$criteria->compare('UserGUID',$this->UserGUID,true);
		$criteria->compare('Provider',$this->Provider,true);
		$criteria->compare('UID',$this->UID,true);
		$criteria->compare('Token',$this->Token,true);
		$criteria->compare('Secret',$this->Secret,true);
		$criteria->compare('DisplayName',$this->DisplayName,true);
		$criteria->compare('UserName',$this->UserName,true);
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