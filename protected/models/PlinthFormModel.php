<?php

/**
 * Form models should extend from this class
 */
abstract class PlinthFormModel extends CFormModel
{
    /**
     * Checks if the file specified has been uploaded and is in the post
     * @param $tcFieldName the name of the field/attribute that we are checking
     * @return bool true if the file is present, false if not
     */
    public function isFilePresent($tcFieldName)
    {
        $lcClassName = get_class($this);
        return (isset($_FILES) &&
            isset($_FILES[$lcClassName]) &&
            isset($_FILES[$lcClassName]['name']) &&
            isset($_FILES[$lcClassName]['name'][$tcFieldName]) &&
            strlen($_FILES[$lcClassName]['name'][$tcFieldName]) > 0);
    }
}