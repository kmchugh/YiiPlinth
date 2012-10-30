<?php

/**
 * This is the model class for table "Country".
 *
 * The followings are the available columns in table 'Country':
 * @property string $CountryID
 * @property string $Name
 * @property string $ISOCode
 * @property string $ISOCode3
 * @property string $ISONumber
 * @property string $FIPSCode
 * @property string $InternetCode
 * @property string $Capital
 * @property string $Continent
 * @property string $Currency
 * @property string $CurrencyCode
 * @property string $PhonePrefix
 * @property string $PostcodeRegex
 * @property string $Languages
 * @property integer $GeoNameID
 * @property string $CreatedDate
 * @property string $CreatedBy
 * @property string $ModifiedDate
 * @property string $ModifiedBy
 * @property string $RowVersion
 *
 * The followings are the available model relations:
 * @property City[] $cities
 * @property CountryIPv4[] $countryIPv4s
 * @property Region[] $regions
 */
class Country extends PlinthModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Country the static model class
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
		return 'Country';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Name, ISOCode, ISOCode3, FIPSCode, Continent, Currency, CurrencyCode, PhonePrefix, PostcodeRegex, Languages', 'required'),
			array('GeoNameID', 'numerical', 'integerOnly'=>true),
			array('Name', 'length', 'max'=>150),
			array('ISOCode, PhonePrefix', 'length', 'max'=>40),
			array('ISOCode3, ISONumber, FIPSCode, InternetCode, Continent, CurrencyCode', 'length', 'max'=>10),
			array('Capital, Currency, PostcodeRegex, Languages', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Name, ISOCode, ISOCode3, ISONumber, FIPSCode, InternetCode, Capital, Continent, Currency, CurrencyCode, PhonePrefix, Languages', 'safe', 'on'=>'search'),
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
			'cities' => array(self::HAS_MANY, 'City', 'CountryID'),
			'countryIPv4s' => array(self::HAS_MANY, 'CountryIPv4', 'CountryID'),
			'regions' => array(self::HAS_MANY, 'Region', 'CountryID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'CountryID' => 'Country',
			'Name' => 'Name',
			'ISOCode' => 'ISO Code',
			'ISOCode3' => 'ISO 3 Code',
			'ISONumber' => 'ISO Number',
			'FIPSCode' => 'FIPS Code',
			'InternetCode' => 'Internet Code',
			'Capital' => 'Capital',
			'Continent' => 'Continent',
			'Currency' => 'Currency',
			'CurrencyCode' => 'Currency Code',
			'PhonePrefix' => 'Phone Prefix',
			'PostcodeRegex' => 'Postcode Regex',
			'Languages' => 'Languages',
			'GeoNameID' => 'Geo Name',
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

		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('ISOCode',$this->ISOCode,true);
		$criteria->compare('ISOCode3',$this->ISOCode3,true);
		$criteria->compare('ISONumber',$this->ISONumber,true);
		$criteria->compare('FIPSCode',$this->FIPSCode,true);
		$criteria->compare('InternetCode',$this->InternetCode,true);
		$criteria->compare('Capital',$this->Capital,true);
		$criteria->compare('Continent',$this->Continent,true);
		$criteria->compare('Currency',$this->Currency,true);
		$criteria->compare('CurrencyCode',$this->CurrencyCode,true);
		$criteria->compare('PhonePrefix',$this->PhonePrefix,true);
		$criteria->compare('PostcodeRegex',$this->PostcodeRegex,true);
		$criteria->compare('Languages',$this->Languages,true);
		$criteria->compare('GeoNameID',$this->GeoNameID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}