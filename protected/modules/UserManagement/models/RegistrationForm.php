<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegistrationForm extends CFormModel
{
    public $email;
    public $email_repeat;
    public $accept_terms;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('email', 'email'),
            array('email, email_repeat', 'required'),
            array('accept_terms', 'required', 'message'=>Utilities::getString('Please accept the terms of service')),
            array('accept_terms', 'boolean'),
            array('email', 'length', 'max'=>255),
            array('email_repeat', 'compare', 'compareAttribute'=>'email'),
            array('email', 'unique', 'className'=>'User', 'attributeName'=>'Email'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'email' => 'Email Address',
            'email_repeat' => 'Verify Email Address',
            'accept_terms' => Utilities::getString('registration agreement'),
        );
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function register()
    {
        if(!$this->hasErrors())
        {
            $loUser = User::create($this->email);
            if (!$loUser->hasErrors())
            {
                $loEvent = new CEvent($this, array("user"=>$loUser));
                Yii::app()->getModule('UserManagement')->onUserRegistered($loEvent);
            }
            $this->addErrors($loUser->getErrors());
        }
        return !$this->hasErrors();
    }
}
