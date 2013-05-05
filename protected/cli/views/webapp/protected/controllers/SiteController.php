<?php
/**
 * Class SiteController Default controller for the site
 */
class SiteController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
}
?>