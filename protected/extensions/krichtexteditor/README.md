KRichTextEditor
===============

KRichTextEditor generates a rich text editor interface using TinyMCE. It is a simple wrapper to [TinyMCE jQuery plugin](http://www.tinymce.com/tryit/jquery_plugin.php).

An example usage would be this in your view, typically `_form`:

```php
<?php
Yii::import('ext.krichtexteditor.KRichTextEditor');
$this->widget('KRichTextEditor', array(
	'model' => $model,
	'value' => $model->isNewRecord ? $model->content : '',
	'attribute' => 'content',
	'options' => array(
		'theme_advanced_resizing' => 'true',
		'theme_advanced_statusbar_location' => 'bottom',
	),
));
```

Default options
---------------

Assigning `$options` would overwrite the `$defaultOptions` that will be passed to JavaScript.

```php
<?php
class KRichTextEditor extends CInputWidget {

...

	public $defaultOptions = array(
		'theme' => 'advanced',
		'theme_advanced_toolbar_location' => 'top',
		'theme_advanced_toolbar_align' => 'left',
		'theme_advanced_buttons1' => "bold,italic,underline,strikethrough,|,fontselect,fontsizeselect",
		'theme_advanced_buttons2' => "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,code,|,forecolor,backcolor",
		'theme_advanced_buttons3' => '',
	);

...

}
```

Page output
-----------------

This is an example of what the browser can render:

![Screenshot of KRichTextEditor](https://github.com/kahwee/yii-extensions/raw/master/protected/extensions/krichtexteditor/KRichTextEditor-screenshot.png "Screenshot of KRichTextEditor")

If you don't load jQuery in your page, KRichTextEditor will load jQuery additionally.

### HTML output

```html
<textarea id="Article_content" name="Article[content]"></textarea>
```

### JavaScript output

```html
<script type="text/javascript" src="/assets/99104da9/jquery.tinymce.js"></script>
<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
	jQuery("#Article_content").tinymce({
		'theme':'advanced',
		'theme_advanced_toolbar_location':'top',
		'theme_advanced_toolbar_align':'left',
		'theme_advanced_buttons1':'bold,italic,underline,strikethrough,|,fontselect,fontsizeselect',
		'theme_advanced_buttons2':'bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,code,|,forecolor,backcolor',
		'theme_advanced_buttons3':'',
		'theme_advanced_resizing':'true',
		'theme_advanced_statusbar_location':'bottom',
		'script_url':'/assets/99104da9/tiny_mce.js'
	});
});
/*]]>*/
</script>
```

More information
----------------

 * [TinyMCE jQuery plugin example](http://www.tinymce.com/tryit/jquery_plugin.php)
 * [TinyMCE configuration options](http://www.tinymce.com/wiki.php/Configuration)