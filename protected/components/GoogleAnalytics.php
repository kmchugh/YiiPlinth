<?php

class GoogleAnalytics
{
	public $key = 'UA-XXXXXXXX-X';

	public function init()
	{
		Yii::app()->clientScript->registerScript(Yii::app()->name.'_googleAnalytics',
			"var _gaq=[['_setAccount','".$this->key."'],['_trackPageview']];
		            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
		            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		            s.parentNode.insertBefore(g,s)}(document,'script'));" ,CClientScript::POS_END);
	}
}

?>