<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class PasswordResetForm extends CFormModel
{
	public $email;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
			array('email', 'email'),
			array('email', 'exist', 'className'=>'User', 'attributeName'=>'Email'),
			array('email, verifyCode', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode' => 'Verification Code'
		);
	}

	public function resetPassword()
	{
		if(!$this->hasErrors())
		{
			$loUser = User::model()->findByAttributes(array('Email' => $this->email));
			if (!is_null($loUser))
			{
                // Create a new token
                $loToken = new ChangePasswordToken();
                $loToken->UserGUID = $loUser->GUID;
                if ($loToken->save())
                {
                    // Send the user an email with a link to change password
                    $loEmail = new YiiMailMessage;
                    $loEmail->view = '//mail/resetPassword';
                    $loEmail->layout = '//layouts/mail';
                    $loEmail->setBody(array('title'=>Utilities::getString('Change your password'),'userModel'=>$loUser, 'resetURL'=>Yii::app()->createAbsoluteUrl('changePassword',array('token'=>$loToken->Token))), 'text/html');
                    $loEmail->subject = Utilities::getString('reset_password_email_subject');
                    $loEmail->addTo($loUser->Email);
                    $loEmail->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminName']));
                    Yii::app()->mail->send($loEmail);
                }
                else
                {
                    $this->addErrors($loToken->getErrors());
                }
			}
			else
			{
				$this->addError('email', 'invalid email address');
			}
		}
		return !$this->hasErrors();
	}
}
