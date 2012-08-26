<?php

/**
 * Base controller class.  This should be the base class
 * for any Plinth controllers
 */
class PlinthController extends Controller
{
	// Initialise the controller, set the user language
	function init()
	{
		parent::init();

		// TODO: Change this to a DB model which will take into account additional languages rather than just the preferred.  E.g. en-US -> en
		if (isset($_POST['_lang']))
		{
			Yii::app()->session['_lang'] = $_POST['_lang'];
		}
		Yii::app()->language = isset(Yii::app()->session['_lang']) ?  Yii::app()->session['_lang'] : Yii::app()->request->getPreferredLanguage();
	}
}

?>