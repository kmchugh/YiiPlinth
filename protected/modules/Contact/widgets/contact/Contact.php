<?php

/**
 * The AccessControl Widget renders the appropriate links
 * allowing the user to either sign in or out.
 */
class Contact extends CWidget
{
    public function init()
    {

    }

    public function run()
    {
        $loModel=new ContactForm();
        $lcFormName='contact-form';

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']===$lcFormName)
        {
            echo CActiveForm::validate($loModel);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['ContactForm']))
        {
            $loModel->attributes=$_POST['ContactForm'];
            // validate user input and redirect to the previous page if valid
            if($loModel->validate() && $loModel->sendContact())
            {
                Yii::app()->user->setFlash('formMessage', 'Thank you, we have received your message and will respond as soon as possible.');
                $loModel = new ContactForm();
            }
        }

        // display the login form
        $this->render('contact',array('toModel'=>$loModel, 'tcFormName'=>$lcFormName));
    }
}

?>