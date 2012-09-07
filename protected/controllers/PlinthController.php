<?php

/**
 * Base controller class.  This should be the base class
 * for any Plinth controllers
 */
class PlinthController extends Controller
{
	/**
	 * Initialises the Controller and sets the user language for this controller
	 */
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

	protected function isAjaxRequest()
	{
		return Yii::app()->request->isAjaxRequest;
	}

	protected function supportsAjaxRequest()
	{
		return true;
	}

	/**
	 * Renders the view specified.  If this is an ajax request then the view will be rendered using renderPartial instead of 
	 * render.  This allows for HTML injection
	 * @param string $view name of the view to be rendered. See {@link getViewFile} for details
	 * about how the view script is resolved.
	 * @param array $data data to be extracted into PHP variables and made available to the view script
	 * @param boolean $return whether the rendering result should be returned instead of being displayed to end users.
	 * @return string the rendering result. Null if the rendering result is not required.
	 * @see renderPartial
	 * @see getLayoutFile
	 */
	public function render($tcView,$taData=null,$tlReturn=false)
	{
		if ($this->supportsAjaxRequest() && $this->isAjaxRequest())
		{
			if($this->beforeRender($tcView))
			{
				$lcOutput=$this->renderPartial($tcView,$taData,true);
				$lcOutput=$this->processOutput($lcOutput);
				if ($tlReturn)
				{
					return $lcOutput;
				}
				else
				{
					echo $lcOutput;
				}
			}
		}
		else
		{
			return parent::render($tcView, $taData, $tlReturn);
		}
	}
}

?>