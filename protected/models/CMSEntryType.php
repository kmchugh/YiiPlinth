<?php

/**
 * This is the model class for table "CMSEntryType".
 *
 * The followings are the available columns in table 'CMSEntryType':
 * @property string $CMSEntryTypeID
 * @property string $Code
 * @property string $Name
 * @property string $Description
 * @property string $GUID
 * @property integer $Sequence
 * @property string $ImageURI
 * @property string $CreatedDate
 * @property string $CreatedBy
 * @property string $ModifiedDate
 * @property string $ModifiedBy
 * @property string $Rowversion
 */
class CMSEntryType extends PlinthModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CMSEntryType the static model class
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
		return 'CMSEntryType';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Code, Name, GUID, Sequence', 'required'),
			array('Sequence', 'numerical', 'integerOnly'=>true),
			array('Code, GUID', 'length', 'max'=>40),
			array('Name', 'length', 'max'=>150),
			array('Description', 'length', 'max'=>500),
			array('ImageURI', 'length', 'max'=>1024),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Code, Name, Description, Sequence', 'safe', 'on'=>'search'),
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
			'CMSEntryTypeID' => 'Cmsentry Type',
			'Code' => 'Code',
			'Name' => 'Name',
			'Description' => 'Description',
			'GUID' => 'Guid',
			'Sequence' => 'Sequence',
			'ImageURI' => 'Image Uri',
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

		$criteria->compare('Code',$this->Code,true);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('Sequence',$this->Sequence);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}