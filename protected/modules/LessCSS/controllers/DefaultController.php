<?php

require_once(dirname(dirname(__FILE__)).'/components/lessc.inc.php');

class DefaultController extends Controller
{
	private $m_cCachePath;

	/**
	 * Initialises the DefaultController
	 */
	public function DefaultController()
	{
		$this->m_cCachePath = Yii::app()->getRuntimePath().'/css/';
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', 
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', 
					'actions'=>array('index'),
					'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('clearcache'),
				'users'=>array('steve.bealing@gmail.com', 'ken@youcommentate.com'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Default action for retrieving a .less file, this will parse the .less file and serve a .css file instead.
	 * @param  String $path the path of the .less file
	 * @param  String $file the name of the .less file, not including the .less extension
	 */
	public function actionIndex($path, $file)
	{
		$lcFile = $this->findFile($path.$file);
		if (is_null($lcFile))
		{
			throw new CHttpException(404,'The requested resource does not exist.');
		}
		$lcCSS = $this->m_cCachePath.$path.'/'.$file.'.css';

		$this->compile($lcFile, $lcCSS);

		header('Content-type: text/css');
		include($lcCSS);
	}

	/**
	 * Clears the cache of all precompiled .less files
	 */
	public function actionClearCache()
	{
		// TODO: Add this to an admin console
		if (file_exists($this->m_cCachePath))
		{
			Utilities::rrmdir($this->m_cCachePath);
		}
	}

	/**
	 * Compiles the specified LESS file and outputs the newly formatted CSS file to the location specified in $tcCSSFile.
	 * If the .css file does not exist or is older than the less file then a compile is done, otherwise the compile is skipped.
	 * This does not check dependencies.
	 * @param  String $tcLessFile the less file to compile
	 * @param  String $tcCSSFile  the css file to write to
	 */
	private function compile($tcLessFile, $tcCSSFile)
	{
		if (!file_exists(dirname($tcCSSFile)))
		{
			mkdir(dirname($tcCSSFile), 0777, true);
		}
		$loLess = new lessc;
		$loLess->checkedCompile($tcLessFile, $tcCSSFile);
	}

	/**
	 * Finds the specified less file, this will look in the current theme directory, then the baseURL if the file was not found or if there is no current theme
	 * @param  String $tcFile the file to find, do not include the .less extension
	 * @return String the full path and filename of the specified file
	 */
	private function findFile($tcFile)
	{
		return Utilities::ISNULL(Utilities::fileExists(getenv("DOCUMENT_ROOT").(is_null(Yii::app()->theme) ? Yii::app()->baseUrl : Yii::app()->theme->baseUrl).'/css/'.$tcFile.'.less'),
			Utilities::fileExists(getenv("DOCUMENT_ROOT").Yii::app()->baseUrl.'/css/'.$tcFile.'.less'));
	}


}