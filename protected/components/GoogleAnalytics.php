<?php

class GoogleAnalytics
{
	public $key = 'UA-XXXXXXXX-X';

	public function init()
	{

		if (isset(Yii::app()->clientScript))
		{
			Yii::app()->clientScript->registerScript(Yii::app()->name.'_googleAnalytics',
                "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '".$this->key."', '".Yii::app()->request->serverName."');
  ga('send', 'pageview');" ,CClientScript::POS_END);





		}
	}
}

?>