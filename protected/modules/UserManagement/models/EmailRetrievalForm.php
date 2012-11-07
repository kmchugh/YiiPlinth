<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class EmailRetrievalForm extends CFormModel
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
            $loOAuthUser = $this->loadOAuthUser();
            if ($loOAuthUser != NULL)
            {
                $loOAuth = new Twitter();
                $loUser = $loOAuth->createUser($loOAuthUser, $this->email, $loOAuth->getUserInfo($loOAuthUser));
                $this->addErrors($loUser->getErrors());
            }
        }
        return !$this->hasErrors();
    }

    private function loadOAuthUser()
    {
        return isset($_SESSION['OAuthUser']) ? OAuthUser::model()->findByPK($_SESSION['OAuthUser']) : NULL;
    }
}
