<?php 
/**
* The PlinthModel class takes care of populating the following Fields
* GUID - A uniquely identifying string for the record
* CreatedDate - The creation date of this record
* CreatedBy - The user creating the record, or 'SYSTEM' if the user cant be determined
* ModifiedDate - The date this record was last modified
* ModifiedBy - The user that last modified this record, or 'SYSTEM'
* RowVersion - A timestamp that can be used for concurrent modification detection
**/
abstract class PlinthModel extends CActiveRecord
{
	/**
	* Occurs before validation happens on the record.  This method ensures the GUID and modified/created properties
	* are populated correctly.
	**/
	protected function beforeValidate()
	{
		$loUser = Yii::app()->user;
		$lcUser = !is_null($loUser) && $loUser->hasState('GUID') ? $loUser->GUID : "SYSTEM";
		if ($this->getIsNewRecord())
		{
			// Set the created date and created user
			$this->CreatedDate = $this->ModifiedDate = $this->Rowversion = Utilities::getTimestamp();
			$this->CreatedBy = $this->ModifiedBy = $lcUser;

			if ($this->hasAttribute('GUID') && is_null($this->GUID))
			{
				$this->GUID = Utilities::getStringGUID();
			}

		}
		else
		{
			$this->ModifiedDate = $this->Rowversion = Utilities::getTimestamp();
			$this->ModifiedBy = $lcUser;
		}

		return parent::beforeValidate();
	}

	// TODO: Override this to make use of multiple connections/dbs.  
	public function getDbConnection()
	{
		return parent::getDbConnection();
	}

	/**
	* Checks if the current user is the owner of this record
	**/
	public function isOwner()
	{
		return is_null($this->CreatedBy) || $this->CreatedBy === Yii::app()->user->GUID;
	}

	public function setAttributes($taValues, $tlSafeOnly=true, $tlCaseInsensitive=false)
	{
		if(!is_array($taValues))
			return;
		$laAttributes = $tlSafeOnly ? $this->getSafeAttributeNames() : $this->attributeNames();
		$laAttributes= array_flip(array_combine($laAttributes, $tlCaseInsensitive ? array_map('strtolower', $laAttributes) : $laAttributes));
		foreach ($taValues as $lcKey => $loValue) 
		{
			$lcKey = $tlCaseInsensitive ? strtolower($lcKey) : $lcKey;
			if (isset($laAttributes[$lcKey]))
			{
				$this[$laAttributes[$lcKey]] = $loValue;
			}
			else if ($tlSafeOnly)
			{
				$this->onUnsafeAttribute($laAttributes[$lcKey], $loValue);
			}
		}
	}
}
?>