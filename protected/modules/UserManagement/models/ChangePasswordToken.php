<?php

/**
 * This is the model class for table "changepasswordtoken".
 *
 * The followings are the available columns in table 'changepasswordtoken':
 * @property string $ChangePasswordToken
 * @property string $UserGUID
 * @property string $Token
 * @property string $Expires
 * @property string $CreatedDate
 * @property string $CreatedBy
 * @property string $ModifiedDate
 * @property string $ModifiedBy
 * @property string $Rowversion
 */
class ChangePasswordToken extends PlinthModel
{
    /**
     * Occurs before validation happens on the record.  This populates the token and expiry
     **/
    protected function beforeValidate()
    {
        if ($this->getIsNewRecord())
        {
            $this->Token = Utilities::getStringGUID();
            $this->Expires = Utilities::getTimestamp() + (24 * 60 * 60 * 1000);
        }

        return parent::beforeValidate();
    }


    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ChangePasswordToken the static model class
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
		return 'ChangePasswordToken';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('UserGUID, Token, Expires', 'required'),
			array('UserGUID, Token', 'length', 'max'=>40),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ChangePasswordToken' => 'Change Password Token',
			'UserGUID' => 'User Guid',
			'Token' => 'Token',
			'Expires' => 'Expires',
			'CreatedDate' => 'Created Date',
			'CreatedBy' => 'Created By',
			'ModifiedDate' => 'Modified Date',
			'ModifiedBy' => 'Modified By',
			'Rowversion' => 'Row Version',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}