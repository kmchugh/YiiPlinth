<?php

/**
 * This is the model class for table "City".
 *
 * The followings are the available columns in table 'City':
 * @property string $CityID
 * @property string $CountryID
 * @property string $RegionID
 * @property string $Name
 * @property string $Code
 * @property double $Latitude
 * @property double $Longitude
 * @property double $TimeZone
 * @property string $CreatedDate
 * @property string $CreatedBy
 * @property string $ModifiedDate
 * @property string $ModifiedBy
 * @property string $RowVersion
 *
 * The followings are the available model relations:
 * @property Region $region
 * @property Country $country
 */
class City extends PlinthModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return City the static model class
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
		return 'City';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CountryID, RegionID, Name, Code', 'required'),
			array('Latitude, Longitude, TimeZone', 'numerical'),
			array('Name', 'length', 'max'=>150),
			array('Code', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('CityID, CountryID, RegionID, Name, Code, Latitude, Longitude, TimeZone', 'safe', 'on'=>'search'),
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
			'region' => array(self::BELONGS_TO, 'Region', 'RegionID'),
			'country' => array(self::BELONGS_TO, 'Country', 'CountryID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'CityID' => 'City',
			'CountryID' => 'Country',
			'RegionID' => 'Region',
			'Name' => 'Name',
			'Code' => 'Code',
			'Latitude' => 'Latitude',
			'Longitude' => 'Longitude',
			'TimeZone' => 'Time Zone',
			'CreatedDate' => 'Created Date',
			'CreatedBy' => 'Created By',
			'ModifiedDate' => 'Modified Date',
			'ModifiedBy' => 'Modified By',
			'RowVersion' => 'Row Version',
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

		$criteria->compare('CountryID',$this->CountryID,true);
		$criteria->compare('RegionID',$this->RegionID,true);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('Code',$this->Code,true);
		$criteria->compare('Latitude',$this->Latitude);
		$criteria->compare('Longitude',$this->Longitude);
		$criteria->compare('TimeZone',$this->TimeZone);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}