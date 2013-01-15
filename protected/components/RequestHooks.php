<?php

class RequestHooks
{
	public function beginRequest(CEvent $toEvent)
	{
		if (isset(Yii::app()->theme))
		{
			if (isset(Yii::app()->session['theme']))
			{
				Yii::app()->theme = Yii::app()->session['theme'];
			}

			if (isset($_REQUEST['requestType']) && $_REQUEST['requestType'] === 'mobile')
			{
				Yii::app()->theme = 'mobile';
			}
			Yii::app()->session['theme'] = Yii::app()->theme->name;
		}
	}
}

?>