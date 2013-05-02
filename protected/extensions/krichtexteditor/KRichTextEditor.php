<?php

/**
 * KRichTextEditor generates a rich text editor interface using tiny mce.
 *
 * An example usage would be:
 *
 *	Yii::import('ext.krichtexteditor.KRichTextEditor');
 *	$this->widget('KRichTextEditor', array(
 *		'model' => $model,
 *		'value' => $model->isNewRecord ? $model->content : '',
 *		'attribute' => 'content',
 *		'options' => array(
 *			'theme_advanced_resizing' => 'true',
 *			'theme_advanced_statusbar_location' => 'bottom',
 *		),
 *	));
 *
 * Assigning options would overwrite the default options that will be
 * passed to tiny mce jquery plugin.
 * The default options are:
 *
 * public $defaultOptions = array(
 * 	'theme' => 'advanced',
 * 	'theme_advanced_toolbar_location' => 'top',
 * 	'theme_advanced_toolbar_align' => 'left',
 * 	'theme_advanced_buttons1' => "bold,italic,underline,strikethrough,|,fontselect,fontsizeselect",
 * 	'theme_advanced_buttons2' => "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,code,|,forecolor,backcolor",
 * 	'theme_advanced_buttons3' => '',
 * );
 *
 * (see {@link http://www.tinymce.com/tryit/jquery_plugin.php}).
 *
 * @author KahWee Teng <t@kw.sg>
 * @version 1.0
 * @link http://kw.sg/
 * @copyright Copyright &copy; 2011 KahWee Teng
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class KRichTextEditor extends CInputWidget {

	/**
	 * @var array options to be passed to tiny mce
	 * @link http://www.tinymce.com/tryit/jquery_plugin.php
	 */
	public $options;

	/**
	 * @var array default options to be passed to tiny mce
	 * @link http://www.tinymce.com/tryit/jquery_plugin.php
	 */
	public $defaultOptions = array(
		'theme' => 'advanced',
		'theme_advanced_toolbar_location' => 'top',
		'theme_advanced_toolbar_align' => 'left',
		'theme_advanced_buttons1' => "bold,italic,underline,strikethrough,|,fontselect,fontsizeselect",
		'theme_advanced_buttons2' => "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,code,|,forecolor,backcolor",
		'theme_advanced_buttons3' => '',
	);

	/**
	 * Executes the widget.
	 * This method registers all needed client scripts and renders
	 * the text area.
	 */
	public function run() {
		list($name, $id) = $this->resolveNameID();
		if (isset($this->htmlOptions['id']))
			$id = $this->htmlOptions['id'];
		else
			$this->htmlOptions['id'] = $id;
		if (isset($this->htmlOptions['name']))
			$name = $this->htmlOptions['name'];

		$this->registerClientScript();
		if ($this->hasModel())
			echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
		else
			echo CHtml::textArea($name, $this->value, $this->htmlOptions);
	}

	/**
	 * Registers the needed CSS and JavaScript.
	 */
	public function registerClientScript() {
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$asset_url = Yii::app()->assetManager->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tiny_mce', false, -1, true);
		$cs->registerScriptFile("$asset_url/jquery.tinymce.js", CClientScript::POS_END);
		$id = $this->htmlOptions['id'];
		$this->options = CMap::mergeArray($this->defaultOptions, $this->options);
		$this->options['script_url'] = "$asset_url/tiny_mce.js";
		$options = $this->options !== array() ? CJavaScript::encode($this->options) : '';
		$js ="jQuery(\"#{$id}\").tinymce({$options});";
		$cs->registerScript('KahWee.KRichTextEditor#' . $id, $js);
	}

}
