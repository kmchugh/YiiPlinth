<?php

class AccessController extends PlinthController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'PlinthCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		Yii::app()->session->clear();
		Yii::app()->session->destroy();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Displays the password reset page
	 */
	public function actionPasswordReset()
	{
		$this->render('passwordReset');
	}

    /**
     * Displays the change password page
     */
    public function actionChangePassword()
    {
        $this->render('changePassword');
    }

	/**
	 * Displays the registration page
	 */
	public function actionRegister()
	{
		Utilities::updateCallbackURL();
		$this->render('registration');
	}

	/**
	 * Displays the registration page
	 */
	public function actionRetrieveEmail()
	{
		Utilities::updateCallbackURL();
		$this->render('retrieveEmail');
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		Utilities::updateCallbackURL();
		$this->render('login');
	}
}