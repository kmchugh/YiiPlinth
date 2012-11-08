<?php 

/**
 * The AccessControl Widget renders the appropriate links 
 * allowing the user to either sign in or out.
 */
class SignIn extends CWidget
{
    public $submenu;
    public $ajaxLink = true;

    public function init()
    {

    }

    public function run()
    {
        $loModel=new LoginForm;
        $lcFormName='login-form';

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']===$lcFormName)
        {
            echo CActiveForm::validate($loModel);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $lcURL = Utilities::getCallbackURL();
            $loModel->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($loModel->validate() && $loModel->login())
            {
                // If this is a mobile request, don't sent do the page
                if (isset($_REQUEST['requestType']) && $_REQUEST['requestType'] === 'mobile')
                {
                    $this->redirect('/?requestType=mobile');
                }
                Utilities::setCallbackURL(NULL);
                Yii::app()->getController()->redirect($lcURL);
            }
        }

        // display the login form
        // // display the registration form
        $lcOutput = $this->render('login',array('toModel'=>$loModel, 'tcFormName'=>$lcFormName), true);
        $loEvent = new CEvent($this, array("form"=>$lcOutput));
        Yii::app()->getModule('UserManagement')->onPrepareSignIn($loEvent);

        // Render the finished form
        echo $loEvent->params['form'];
    }
}

?>