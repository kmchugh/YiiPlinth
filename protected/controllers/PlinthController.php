<?php

/**
 * Base controller class.  This should be the base class
 * for any Plinth controllers
 */
class PlinthController extends Controller
{
	private $m_aProperties;

	public $theme;

	/**
	 * Initialises the Controller and sets the user language for this controller
	 */
	function init()
	{
		parent::init();

		if (isset($this->theme))
		{
			Yii::app()->theme = $this->theme;
			Yii::app()->session['theme'] = $this->theme;
		}

		// TODO: Change this to a DB model which will take into account additional languages rather than just the preferred.  E.g. en-US -> en
		if (isset($_POST['_lang']))
		{
			Yii::app()->session['_lang'] = $_POST['_lang'];
		}
		Yii::app()->language = isset(Yii::app()->session['_lang']) ?  Yii::app()->session['_lang'] : Yii::app()->request->getPreferredLanguage();


	}

	/**
	 * This method is invoked right before an action is to be executed (after all possible filters.)
	 * You may override this method to do last-minute preparation for the action.
	 * @param CAction $action the action to be executed.
	 * @return boolean whether the action should be executed.
	 */
	protected function beforeAction($action)
	{
		$this->layout = LayoutMapManager::getLayout($this);
		return true;
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

	/**
	 * Finds a view file based on its name.
	 * The view name can be in one of the following formats:
	 * <ul>
	 * <li>absolute view within a module: the view name starts with a single slash '/'.
	 * In this case, the view will be searched for under the currently active module's view path.
	 * If there is no active module, the view will be searched for under the application's view path.</li>
	 * <li>absolute view within the application: the view name starts with double slashes '//'.
	 * In this case, the view will be searched for under the application's view path.
	 * This syntax has been available since version 1.1.3.</li>
	 * <li>aliased view: the view name contains dots and refers to a path alias.
	 * The view file is determined by calling {@link YiiBase::getPathOfAlias()}. Note that aliased views
	 * cannot be themed because they can refer to a view file located at arbitrary places.</li>
	 * <li>relative view: otherwise. Relative views will be searched for under the currently active
	 * controller's view path.</li>
	 * </ul>
	 * For absolute view and relative view, the corresponding view file is a PHP file
	 * whose name is the same as the view name. The file is located under a specified directory.
	 * This method will call {@link CApplication::findLocalizedFile} to search for a localized file, if any.
	 * @param string $viewName the view name
	 * @param string $viewPath the directory that is used to search for a relative view name
	 * @param string $basePath the directory that is used to search for an absolute view name under the application
	 * @param string $moduleViewPath the directory that is used to search for an absolute view name under the current module.
	 * If this is not set, the application base view path will be used.
	 * @return mixed the view file path. False if the view file does not exist.
	*/
	public function resolveViewFile($tcViewName,$tcViewPath,$tcBasePath,$tcModuleViewPath=null)
	{
		if(empty($tcViewName))
		{
			return false;
		}

		if($tcModuleViewPath===null)
		{
			$tcModuleViewPath=$tcBasePath;
		}

		$lcExtension = (($lcRenderer=Yii::app()->getViewRenderer())!==null) ?
			$lcExtension=$lcRenderer->fileExtension :
			$lcExtension='.php';

		if($tcViewName[0]==='/')
		{
			$lcViewFile = (strncmp($tcViewName,'//',2)===0) ?
				(($lcExtension==='.php' ?
						!is_file($tcBasePath.$tcViewName.$lcExtension) :
						!is_file($tcBasePath.$tcViewName.'.php')) ?
							YiiBase::getPathOfAlias('YIIPlinth.views.'.$tcViewName) :
							$tcBasePath.$tcViewName) :
			$tcModuleViewPath.$tcViewName;
		}
		else if(strpos($tcViewName,'.'))
		{
			$lcViewFile=Yii::getPathOfAlias($tcViewName);
		}
		else
		{
			$lcViewFile=$tcViewPath.DIRECTORY_SEPARATOR.$tcViewName;
		}


		if(is_file($lcViewFile.$lcExtension))
		{
			return Yii::app()->findLocalizedFile($lcViewFile.$lcExtension);
		}
		else if($lcExtension!=='.php' && is_file($lcViewFile.'.php'))
		{
			return Yii::app()->findLocalizedFile($lcViewFile.'.php');
		}
		else
		{
			return false;
		}
	}
}

?>