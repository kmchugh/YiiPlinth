<?php

/**
 * LoginForm Class handles the data structures and processes for 
 * interaction with the login form
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $m_oUserIdentity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),

			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),

			// User name must be formatted as an email address
			array('username', 'email'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username' => 'Email Address',
			'password' => 'Password',
			'rememberMe'=>'Remember me next time',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->m_oUserIdentity=new PlinthUserIdentity($this->username,$this->password);
			if(!$this->m_oUserIdentity->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->m_oUserIdentity===null)
		{
			$this->m_oUserIdentity=new PlinthUserIdentity($this->username,$this->password);
			$this->m_oUserIdentity->authenticate();
		}
		if($this->m_oUserIdentity->errorCode===PlinthUserIdentity::ERROR_NONE)
		{
			$lnDuration=$this->rememberMe ? 3600*24*30 : 0;
			Yii::app()->user->login($this->m_oUserIdentity,$lnDuration);

			return true;
		}
		return false;
	}
}
