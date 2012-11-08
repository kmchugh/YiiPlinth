<?php 

/**
 * The AccessControl Widget renders the appropriate links 
 * allowing the user to either sign in or out.
 */
class PasswordReset extends CWidget
{
    public function init()
    {

    }

    public function run()
    {
        $loModel=new PasswordResetForm;
        $lcFormName='passwordReset-form';

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']===$lcFormName)
        {
            echo CActiveForm::validate($loModel);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['PasswordResetForm']))
        {
            $loModel->attributes=$_POST['PasswordResetForm'];
            // validate user input and redirect to the previous page if valid
            if($loModel->validate() && $loModel->resetPassword())
            {
                Yii::app()->user->setFlash('formMessage', 'An email has been sent to '.$loModel->email.' with your account details');
                Yii::app()->getController()->redirect('login');
            }
        }
        // display the login form
        $this->render('passwordReset',array('toModel'=>$loModel, 'tcFormName'=>$lcFormName));
    }
}

?>