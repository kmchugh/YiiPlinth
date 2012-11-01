<?php

/**
 * This is the model class for table "CountryIPv4".
 *
 * The followings are the available columns in table 'CountryIPv4':
 * @property string $CountryIPv4ID
 * @property string $CountryID
 * @property string $StartIP
 * @property string $EndIP
 * @property string $RegistryName
 * @property string $AllocatedDate
 * @property string $CreatedDate
 * @property string $CreatedBy
 * @property string $ModifiedDate
 * @property string $ModifiedBy
 * @property string $Rowversion
 *
 * The followings are the available model relations:
 * @property Country $country
 */
class CountryIPv4 extends PlinthModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CountryIPv4 the static model class
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
		return 'CountryIPv4';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('StartIP, EndIP, RegistryName, AllocatedDate', 'required'),
			array('RegistryName', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('CountryID, StartIP, EndIP, RegistryName, AllocatedDate', 'safe', 'on'=>'search'),
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
			'country' => array(self::BELONGS_TO, 'Country', 'CountryID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'CountryIPv4ID' => 'Country IP ID',
			'CountryID' => 'Country',
			'StartIP' => 'Start IP',
			'EndIP' => 'End IP',
			'RegistryName' => 'Registry Name',
			'AllocatedDate' => 'Allocated Date',
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

		$criteria->compare('CountryID',$this->CountryID,true);
		$criteria->compare('StartIP',$this->StartIP,true);
		$criteria->compare('EndIP',$this->EndIP,true);
		$criteria->compare('RegistryName',$this->RegistryName,true);
		$criteria->compare('AllocatedDate',$this->AllocatedDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Retrieves the Country model for the specified IP address.  If the ip address does not have
	 * an associated country, NULL will be returned.
	 * @param  string $tcIPv4Address the ip address to retrieve the country for
	 * @return Country                the country model for the specified IP or NULL
	 */
	public static function getCountryForIP($tcIPv4Address)
	{
		if ($tcIPv4Address === '')
	    	{
	    		$tcIPv4Address = 0;
	    	}
	    	else
	    	{
	    		$tcIPv4Address = explode('.', $tcIPv4Address);
	    		$tcIPv4Address = ($tcIPv4Address[3] + $tcIPv4Address[2] * 256 +
	    			$tcIPv4Address[1] * 256 * 256 +
	    			$tcIPv4Address[0] * 256 * 256 * 256);
	    	}

	    	$loCountryIP = CountryIPv4::model()->find(array(
			'condition'=>':ipAddress BETWEEN StartIP AND EndIP',
			'params'=>array(':ipAddress'=>$tcIPv4Address),
			));
		return $loCountryIP !== NULL ? $loCountryIP->country : NULL;

	}
}