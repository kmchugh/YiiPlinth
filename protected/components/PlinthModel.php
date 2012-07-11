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
}
?>