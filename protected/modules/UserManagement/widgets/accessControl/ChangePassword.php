<?php

/**
 * The AccessControl Widget renders the appropriate links
 * allowing the user to either sign in or out.
 */
class ChangePassword extends CWidget
{
    public function init()
    {

    }

    public function run()
    {
        $loModel=new ChangePasswordForm;
        $lcFormName='changePassword-form';

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']===$lcFormName)
        {
            echo CActiveForm::validate($loModel);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['ChangePasswordForm']))
        {
            ChangePasswordToken::model()->deleteAll('Expires < :currentTime', array(':currentTime'=>Utilities::getTimestamp()));

            $loToken = isset($_GET['token']) ?
                ChangePasswordToken::model()->findByAttributes(array('Token'=>$_GET['token'])) :
                NULL;
            $loUser = User::model()->findByAttributes(array('GUID'=>is_null($loToken) ? Yii::app()->user->GUID : $loToken->UserGUID));
            $loModel->attributes=$_POST['ChangePasswordForm'];
            // validate user input and redirect to the previous page if valid
            if($loModel->validate() && $loModel->changePassword($loUser, $loModel->password))
            {
                Yii::app()->user->setFlash('formMessage', 'Your password has been changed '.$loUser->DisplayName);
                if (!is_null($loToken))
                {
                    $loToken->delete();
                }

                // Send the user an email with a link to change password
                $loEmail = new PlinthMail($loUser->Email,
                    Utilities::getString('change_password_email_subject'),
                    array(
                        'title'=>'Your password has been reset',
                        'userModel'=>$loUser),
                    '//mail/changePassword',
                    '//layouts/mail'
                );
                $loEmail->send();

                // We know the users name and password at this stage so be helpful and log them in
                if (Yii::app()->user->isGuest)
                {
                    $loLoginForm = new LoginForm();
                    $loLoginForm->username = $loUser->Email;
                    $loLoginForm->password  =$loModel->password;
                    $loLoginForm->login();
                }

                // TODO: This should redirect back to where we came from
                Yii::app()->getController()->redirect(Yii::app()->user->isGuest ? '/login' : '/');
            }
        }
        // display the login form
        $this->render('changePassword',array('toModel'=>$loModel, 'tcFormName'=>$lcFormName));
    }
}

?>