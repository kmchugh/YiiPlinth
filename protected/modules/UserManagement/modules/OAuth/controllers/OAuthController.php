<?php

abstract class OAuthController extends Controller
{
    protected abstract function createOAuth();

    public function actionIndex()
    {
        Utilities::setCallbackURL(NULL);
        Utilities::updateCallbackURL();
        $loOauth = $this->createOAuth();
        if ($loOauth->hasAPIKey())
        {
            $loToken = $loOauth->getRequestToken(Utilities::getURL().'/default/Callback');
        }
        else
        {
            echo 'API Keys have not been set for '.$loOauth->getProviderName();
        }
    }



    public function actionCallback()
    {
        $loOauth = $this->createOAuth();
        if ($loOauth->handleOAuthResponse($_REQUEST))
        {
            // Request was okay, so redirect
            Yii::app()->getController()->redirect(preg_match('/.+?\/login/', Utilities::getCallbackURL()) ? '/' : Utilities::getCallbackURL());
        }
        else
        {
            // No OAuth User, redirect to login
            Yii::app()->getController()->redirect('/login');
        }
    }

}