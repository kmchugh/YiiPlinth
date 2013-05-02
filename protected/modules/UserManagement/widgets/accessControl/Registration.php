<?php 

/**
 * The AccessControl Widget renders the appropriate links 
 * allowing the user to either sign in or out.
 */
class Registration extends CWidget
{
    public $submenu;
    public $ajaxLink = true;
    public $redirect = '/login';

    public function init()
    {

    }

    public function run()
    {
        $loModel=new RegistrationForm;
        $lcFormName='registration-form';

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']===$lcFormName)
        {
            echo CActiveForm::validate($loModel);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['RegistrationForm']))
        {
            $loModel->attributes=$_POST['RegistrationForm'];
            // validate user input and redirect to the previous page if valid
            if($loModel->validate() && $loModel->register())
            {
                Yii::app()->user->setFlash('formMessage', 'An email has been sent to '.$loModel->email.' with your account details');
                Yii::app()->getController()->redirect($this->redirect);
            }
        }

        // display the registration form
        $lcOutput = $this->render('registration',array('toModel'=>$loModel, 'tcFormName'=>$lcFormName), true);
        $loEvent = new CEvent($this, array("form"=>$lcOutput));
        Yii::app()->getModule('UserManagement')->onPrepareRegistration($loEvent);

        // Render the finished form
        echo $loEvent->params['form'];
    }
}

?>