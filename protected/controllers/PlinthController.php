<?php

/**
 * Base controller class.  This should be the base class
 * for any Plinth controllers
 */
class PlinthController extends Controller
{
	private $m_aProperties;

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

	/**
	 * Sets the property specified by tcName to the value taValue.  This value
	 * can only be retrieved by getProeprty
	 * @param String $tcName the property to set
	 * @param String $toValue the value of the property
	 */
	public function setProperty($tcName, $toValue)
	{
		if (!isset($this->m_aProperties))
		{
			$this->m_aProperties = array();
		}
		$this->m_aProperties[$tcName] = $toValue;
	}

	/**
	 * Checks if a property has been set
	 * @param  String $tcName the property to check
	 * @return boolean   true if the property has been set
	 */
	public function hasProperty($tcName)
	{
		return isset($this->m_aProperties) && isset($this->m_aProperties[$tcName]);
	}

	/**
	 * Gets the value that has been set, if a value has not been set then this will
	 * return null
	 * @param  String $tcName the property to retrieve the value for
	 * @return variable the value that had been stored
	 */
	public function getProperty($tcName)
	{
		return isset($this->m_aProperties[$tcName]) ?  $this->m_aProperties[$tcName] : NULL;
	}

	/**
	 * Clears a property, this will unset the property 
	 * @param  String $tcName the name of the property to clear
	 */
	public function clearProperty($tcName)
	{
		if (isset($this->m_aProperties))
		{
			unset($this->m_aProperties[$tcName]);
		}
	}

	/**
	 * Checks if this request was an ajax request
	 * @return boolean true if this is an ajax request, false otherwise
	 */
	protected function isAjaxRequest()
	{
		return Yii::app()->request->isAjaxRequest;
	}

	/**
	 * Checks if this Controller can support ajax requests
	 * @return boolean true if this controller can support an ajax request
	 */
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