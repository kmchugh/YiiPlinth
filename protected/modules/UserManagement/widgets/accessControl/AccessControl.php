<?php 

/**
 * The AccessControl Widget renders the appropriate links 
 * allowing the user to either sign in or out.
 */
class AccessControl extends CWidget
{
	public $submenu;

	public function init()
	{

	}

	public function run()
	{
		$this->render('accessControl');
	}
}

?>