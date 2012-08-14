<?php

/**
 * This is a fix for the captcha action ajax calls.  The framework captcha action displays session warnings which can
 * cause the response to return non json data
 */
class PlinthCaptchaAction extends CCaptchaAction
{
	/**
	 * Runs the action.  This is the same as the CCaptchaAction except for the suppression of 
	 */
	public function run()
	{
		@parent::run();
		/*
		if(isset($_GET[self::REFRESH_GET_VAR]))  // AJAX request for regenerating code
		{
			$code=$this->getVerifyCode(true);
			echo CJSON::encode(array(
				'hash1'=>$this->generateValidationHash($code),
				'hash2'=>$this->generateValidationHash(strtolower($code)),
				// we add a random 'v' parameter so that FireFox can refresh the image
				// when src attribute of image tag is changed
				'url'=>$this->getController()->createUrl($this->getId(),array('v' => uniqid())),
			));
		}
		else
			$this->renderImage($this->getVerifyCode());
		Yii::app()->end();
		 */
	}
}

?>