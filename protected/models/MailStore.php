<?php

/**
 * This is the model class for table "MailStore".
 *
 * The followings are the available columns in table 'MailStore':
 * @property string $MailStoreID
 * @property string $View
 * @property string $Layout
 * @property string $Subject
 * @property string $To
 * @property string $From           // Used to store the GUID of the user that caused the action to create the email
 * @property string $Parameters
 * @property string $Hash
 * @property string $CreatedDate
 * @property string $CreatedBy
 * @property string $ModifiedDate
 * @property string $ModifiedBy
 * @property string $Rowversion
 */
class MailStore extends PlinthModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MailStore the static model class
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
		return 'MailStore';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('View, Layout, Subject, To', 'required'),
			array('View, Layout, Subject', 'length', 'max'=>255),
            array('Hash', 'unique'),
			array('To', 'length', 'max'=>1024),
			array('From', 'length', 'max'=>40),
			array('Parameters', 'safe'),
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
			'MailStoreID' => 'Mail Store',
			'View' => 'View',
			'Layout' => 'Layout',
			'Subject' => 'Subject',
			'To' => 'To',
			'From' => 'From',
			'Parameters' => 'Parameters',
			'Hash' => 'Hash',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * Update the HASH
     * @return bool
     */
    protected function beforeValidate()
    {
        $this->Hash = md5($this->To.'_|_'.
                          $this->Subject.'_|_'.
                          $this->View.'_|_'.
                          $this->Layout.'_|_'.
                          $this->Parameters.'_|_');

        return parent::beforeValidate();
    }
}