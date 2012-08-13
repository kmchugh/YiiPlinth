<?php

class AccessController extends Controller
{
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$loModel=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($loModel);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$loModel->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($loModel->validate() && $loModel->login())
			{
				// If this is a mobile request, don't sent do the page
				if (isset($_REQUEST['requestType']) && $_REQUEST['requestType'] === 'mobile')
				{
					$this->redirect(array('/','requestType'=>'mobile'));
				}
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		// display the login form
		$this->render('login',array('model'=>$loModel));
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
}