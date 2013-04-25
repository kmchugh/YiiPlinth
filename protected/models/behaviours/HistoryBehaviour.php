<?php
class HistoryBehaviour
    extends CActiveRecordBehavior
{
    /**
     * Occurs before validation happens on the record.  This method ensures the GUID and modified/created properties
     * are populated correctly.
     **/
    public function beforeValidate($toEvent)
    {
        $loUser = Yii::app()->user;
        $lcUser = !is_null($loUser) && $loUser->hasState('PLINTHUSER') ? Utilities::ISNULLOREMPTY($loUser->GUID, "SYSTEM") : "SYSTEM";
        $loOwner = $this->getOwner();

        if ($loOwner->getIsNewRecord())
        {
            // Set the created date and created user
            $loOwner->CreatedDate = $loOwner->ModifiedDate = $loOwner->Rowversion = Utilities::getTimestamp();
            $loOwner->CreatedBy = $loOwner->ModifiedBy = $lcUser;

            if ($loOwner->hasAttribute('GUID') && is_null($loOwner->GUID))
            {
                $loOwner->GUID = Utilities::getStringGUID();
            }
        }
        else
        {
            $loOwner->ModifiedDate = $loOwner->Rowversion = Utilities::getTimestamp();
            $loOwner->ModifiedBy = $lcUser;
        }

        return parent::beforeValidate($toEvent);
    }
}


?>