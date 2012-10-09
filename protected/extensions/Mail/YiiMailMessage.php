<?php
/**
* YiiMailMessage class file.
*
* @author Jonah Turnquist <poppitypop@gmail.com>
* @link https://code.google.com/p/yii-mail/
* @package Yii-Mail
*/

/**
* Any requests to set or get attributes or call methods on this class that are 
* not found in that class are redirected to the {@link Swift_Mime_Message} 
* object.
* 
* This means you need to look at the Swift Mailer documentation to see what 
* methods are availiable for this class.  There are a <b>lot</b> of methods, 
* more than I wish to document.  Any methods availiable in 
* {@link Swift_Mime_Message} are availiable here.
* 
* Documentation for the most important methods can be found at 
* {@link http://swiftmailer.org/docs/messages}
* 
* The YiiMailMessage component also allows using a shorthand for methods in 
* {@link Swift_Mime_Message} that start with set* or get*
* For instance, instead of calling $message->setFrom('...') you can use 
* $message->from = '...'.
* 
* Here are a few methods to get you started:
* <ul>
* 	<li>setSubject('Your subject')</li>
* 	<li>setFrom(array('john@doe.com' => 'John Doe'))</li>
* 	<li>setTo(array('receiver@domain.org', 'other@domain.org' => 'Name'))</li>
* 	<li>attach(Swift_Attachment::fromPath('my-document.pdf'))</li>
* </ul>
*/
class YiiMailMessage extends CComponent 
{
	/**
	* @var string the view to use for rendering the body, null if no view is 
	* used.  An extra variable $mail will be passed to the view .which you may 
	* use to set e.g. the email subject from within the view
	*/
	public $view;

	/**
	* @var Swift_Mime_Message
	*/
	public $message;

	/**
	 * Default layout to use for email messages
	 * @var string the layout to use
	 */
	public $layout = 'email';

	/**
	* Any requests to set or get attributes or call methods on this class that 
	* are not found are redirected to the {@link Swift_Mime_Message} object.
	* @param string the attribute name
	*/
	public function __get($name) {
		try {
			return parent::__get($name);
		} catch (CException $e) {
			$getter = 'get'.$name;
			if(method_exists($this->message, $getter))
				return $this->message->$getter();
			else
				throw $e;
		}
	}

	/**
	* Any requests to set or get attributes or call methods on this class that 
	* are not found are redirected to the {@link Swift_Mime_Message} object.
	* @param string the attribute name
	*/
	public function __set($name, $value) {
		try {
			return parent::__set($name, $value);
		} catch (CException $e) {
			$setter = 'set'.$name;
			if(method_exists($this->message, $setter))
				$this->message->$setter($value);
			else
				throw $e;
		}
	}

	/**
	* Any requests to set or get attributes or call methods on this class that 
	* are not found are redirected to the {@link Swift_Mime_Message} object.
	* @param string the method name
	*/
	public function __call($name, $parameters) {
		try {
			return parent::__call($name, $parameters);	
		} catch (CException $e) {
			if(method_exists($this->message, $name))
				return call_user_func_array(array($this->message, $name), $parameters);
			else
				throw $e;
		}
	}

	/**
	* You may optionally set some message info using the paramaters of this 
	* constructor.
	* Use {@link view} and {@link setBody()} for more control.
	* 
	* @param string $subject
	* @param string $body
	* @param string $contentType
	* @param string $charset
	* @return Swift_Mime_Message
	*/
	public function __construct($subject = null, $body = null, $contentType = null, $charset = null) {
		Yii::app()->mail->registerScripts();
		$this->message = Swift_Message::newInstance($subject, $body, $contentType, $charset);
	}

	/**
	* Set the body of this entity, either as a string, or array of view 
	* variables if a view is set, or as an instance of 
	* {@link Swift_OutputByteStream}.
	* 
	* @param mixed the body of the message.  If a $this->view is set and this 
	* is a string, this is passed to the view as $body.  If $this->view is set 
	* and this is an array, the array values are passed to the view like in the 
	* controller render() method
	* @param string content type optional. For html, set to 'html/text'
	* @param string charset optional
	*/
	public function setBody($tcBody = '', $tcContentType = null, $tcCharset = null)
	{
		if ($this->view !== null)
		{
			if (!is_array($tcBody)) 
			{
				$tcBody = array('body'=>$tcBody);
			}

			// Create a dummy controller if needed
			$loController = isset(Yii::app()->controller) ?
						Yii::app()->controller :
						new PlinthController('YiiMail');

			$lcLayoutFile = $loController->getLayoutFile($this->layout);
			$lcViewFile = $this->resolveViewFile($loController, $this->view);

			$laData =  array_merge(array('subject'=>$this->subject),
					$tcBody,
					array('mail'=>$this));

			// renderPartial won't work with CConsoleApplication, so use 
			// renderInternal - this requires that we use an actual path to the 
			// view rather than the usual alias
			$laData['content'] = $loController->renderInternal($lcViewFile, $laData, true);
			$tcBody = $loController->renderInternal($lcLayoutFile, $laData, true);
		}
		return $this->message->setBody($tcBody, $tcContentType, $tcCharset);
	}

	public function resolveViewFile($toController, $tcView)
	{
		if(empty($tcView))
		{
			return false;
		}

		$lcBasePath = !is_null(Yii::app()->getTheme()) ?
			Yii::app()->getTheme()->getBasePath().DIRECTORY_SEPARATOR.'views':
			Yii::getPathOfAlias('application.views');

		$lcExtension = (($lcRenderer=Yii::app()->getViewRenderer())!==null) ?
			$lcExtension=$lcRenderer->fileExtension :
			$lcExtension='.php';

		if($tcView[0]==='/')
		{
			if  (strncmp($tcView,'//',2)===0)
			{
				if (is_file($lcBasePath.$tcView.$lcExtension))
				{
					// Check Theme
					$lcViewFile = $lcBasePath.$tcView;
				}
				else if (is_file(YiiBase::getPathOfAlias('application.views.'.$tcView).$lcExtension))
				{
					// Check Application
					$lcViewFile = YiiBase::getPathOfAlias('application.views.'.$tcView);
				}
				else if (is_file(YiiBase::getPathOfAlias('YIIPlinth.views.'.$tcView).$lcExtension))
				{
					// Check YIIPlinth
					$lcViewFile = YiiBase::getPathOfAlias('YIIPlinth.views.'.$tcView);
				}
				else
				{
					$lcViewFile = $lcBasePath.$tcView;
				}
			}
			else
			{
				$lcViewFile = $lcBasePath.$tcView;
			}
		}
		else if(strpos($tcView,'.'))
		{
			$lcViewFile=Yii::getPathOfAlias($tcView);
		}
		else
		{
			$lcViewFile=$lcBasePath.DIRECTORY_SEPARATOR.$tcView;
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