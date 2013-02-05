<?php

/**
 * This is the model class for table "CMSEntry".
 *
 * The followings are the available columns in table 'CMSEntry':
 * @property string $CMSEntryID
 * @property string $TypeID
 * @property string $Section
 * @property string $Title
 * @property integer $Sequence
 * @property string $Text
 * @property string $CreatedDate
 * @property string $CreatedBy
 * @property string $ModifiedDate
 * @property string $ModifiedBy
 * @property string $Rowversion
 */
class CMSEntry extends PlinthModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CMSEntry the static model class
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
		return 'CMSEntry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TypeID, Section, Title, Sequence', 'required'),
			array('Sequence', 'numerical', 'integerOnly'=>true),
			array('TypeID', 'length', 'max'=>20),
			array('Section, Title', 'length', 'max'=>255),
			array('Text', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('TypeID, Section, Title, Sequence, Text', 'safe', 'on'=>'search'),
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
			'CMSEntryID' => 'Cmsentry',
			'TypeID' => 'Type',
			'Section' => 'Section',
			'Title' => 'Title',
			'Sequence' => 'Sequence',
			'Text' => 'Text',
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

		$criteria->compare('TypeID',$this->TypeID,true);
		$criteria->compare('Section',$this->Section,true);
		$criteria->compare('Title',$this->Title,true);
		$criteria->compare('Sequence',$this->Sequence);
		$criteria->compare('Text',$this->Text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}