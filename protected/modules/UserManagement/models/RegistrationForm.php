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
            $lcPassword = substr(Utilities::getStringGUID(), 0, 10);

            $loUser = new User;
            $loUser->setAttributes(
                array(
                'Email' => $this->email, 
                'DisplayName' => substr($this->email, 0, strpos($this->email, '@')),
                'StartDate' => Utilities::getTimeStamp(),
                ), false);
            $loUser->resetPassword($lcPassword);
            if ($loUser->save())
            {
                // Send the user an email
                $loEmail = new YiiMailMessage;
                $loEmail->view = '//mail/userRegistration';
                $loEmail->layout = '//layouts/mail';
                $loEmail->setBody(array('userModel'=>$loUser, 'password' => $lcPassword), 'text/html');
                $loEmail->subject = 'Welcome to YouCommentate.  Time to start calling it as you see it!';
                $loEmail->addTo($loUser->Email);
                $loEmail->from = Yii::app()->params['adminEmail'];
                Yii::app()->mail->send($loEmail);
            }
            else
            {
                $this->addErrors($loUser->getErrors());
            }
        }
        return !$this->hasErrors();
    }
}
