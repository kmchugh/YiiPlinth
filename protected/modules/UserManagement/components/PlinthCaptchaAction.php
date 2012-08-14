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
	}
}

?>