<?php

/**
 * ChangePassword form class is used for allowing the user to change their password
 */
class ChangePasswordForm extends CFormModel
{
    public $password;
    public $password_repeat;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('password', 'length', 'max'=>255, 'min'=>3),
            array('password, password_repeat', 'required'),
            array('password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>'Passwords must match exactly'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'password' => 'New Password',
            'password_repeat' => 'Confirm New Password',
        );
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function changePassword($toUser, $tcPassword)
    {
        if(!$this->hasErrors())
        {
            if (!is_null($toUser))
            {
                $toUser->resetPassword($tcPassword);
                if ($toUser->save())
                {
                    $loEvent = new CEvent($this, array("user"=>$toUser));
                    Yii::app()->getModule('UserManagement')->onPasswordReset($loEvent);
                }
                else
                {
                    $this->addErrors($toUser->getErrors());
                }
            }
            else
            {
                $this->addError('password', 'invalid user');
            }
        }
        return !$this->hasErrors();
    }
}
