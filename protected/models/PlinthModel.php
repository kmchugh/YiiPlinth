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
    protected $slug = null;

    public function __construct($tcScenario = 'insert')
    {
        parent::__construct($tcScenario);

        if (!is_null($this->slug))
        {
            $loBehaviour = new SlugBehaviour();
            $loBehaviour->slug = $this->slug;
            $this->attachBehavior("sluggable", $loBehaviour);
        }

        $this->attachBehavior("history", new HistoryBehaviour());
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

    /**
     * Sets the attributes of the model from an associative array.
     * @param $taValues the associative array to set the values from
     * @param bool $tlSafeOnly true for only safe values
     * @param bool $tlCaseInsensitive true for allowing case insentitivity on attribute name checking
     */
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