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
		$loModel=new PasswordResetForm;
		$lcFormName='passwordReset-form';

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']===$lcFormName)
		{
			echo CActiveForm::validate($loModel);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['PasswordResetForm']))
		{
			$loModel->attributes=$_POST['PasswordResetForm'];
			// validate user input and redirect to the previous page if valid
			if($loModel->validate() && $loModel->resetPassword())
			{
				Yii::app()->user->setFlash('formMessage', 'An email has been sent to '.$loModel->email.' with your account details');
				$this->redirect('login');
			}
		}
		// display the login form
		$this->render('passwordReset',array('toModel'=>$loModel, 'tcFormName'=>$lcFormName));
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