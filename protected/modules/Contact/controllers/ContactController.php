<?php

class ContactController extends PlinthController
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            'captcha'=>array(
                'class'=>'PlinthCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
        );
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionIndex()
    {
        $this->render('contact');
    }
}