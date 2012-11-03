<?php 

/**
 * The AccessControl Widget renders the appropriate links 
 * allowing the user to either sign in or out.
 */
class OAuthProviders extends CWidget
{
    public $OAuthLinks = array();

    public function init()
    {

    }

    public function run()
    {
        $this->render('oauthProvider', array('OAuthProviders'=>$this->OAuthLinks));
    }
}

?>