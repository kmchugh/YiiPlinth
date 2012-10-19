<?php

Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.components.*');
Yii::import('YIIPlinth.modules.UserManagement.modules.OAuth.modules.Twitter.components.*');
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
                $loUser = User::create($this->email);
                $this->addErrors($loUser->getErrors());

                if(!$this->hasErrors())
                {
                    $loOAuth = new Twitter();
                    $loOAuthInfo = $loOAuth->getUserInfo($loOAuthUser);

                    // Update the User
                    if (!is_null($loOAuthInfo))
                    {
                        $loUser->DisplayName = $loOAuthInfo->screen_name;
                        $loUser->save();
                    }

                    $laName = explode(' ', !is_null($loOAuthInfo) ? strtoupper($loOAuthInfo->name): $loUser->DisplayName);
                    if (count($laName) < 2)
                    {
                        $laName[1]='';
                    }

                    $loUserInfo = new UserInfo();
                    $loUserInfo->UserID=$loUser->UserID;
                    $loUserInfo->Country = !is_null($loOAuthInfo) ? strtoupper($loOAuthInfo->location) : NULL;
                    $loUserInfo->ProfileImageURI = !is_null($loOAuthInfo) ? $loOAuthInfo->profile_image_url : NULL;
                    $loUserInfo->FirstName = $laName[0];
                    $loUserInfo->LastName = $laName[count($laName)-1];
                    $loUserInfo->Description = !is_null($loOAuthInfo) ? $loOAuthInfo->description : '';
                    $loUserInfo->save();

                    $this->addErrors($loUserInfo->getErrors());

                    $loOAuthUser->UserID=$loUser->UserID;
                    $loOAuthUser->UserGUID=$loUser->GUID;
                    $loOAuthUser->UserName=$loUser->Email;
                    $loOAuthUser->save();

                    $loUserIdentity=new PlinthUserIdentity($loUser->Email,$loUser->Password);
                    Yii::app()->user->login($loUserIdentity,3600*24*30);
                }
            }
        }
        return !$this->hasErrors();
    }

    private function loadOAuthUser()
    {
        return isset($_SESSION['OAuthUser']) ? OAuthUser::model()->findByPK($_SESSION['OAuthUser']) : NULL;
    }
}
