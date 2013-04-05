<?php 

class EmailRetrieval extends CWidget
{
    public $authUser;

    public function init()
    {

    }

    public function run()
    {
        $loModel=new EmailRetrievalForm;
        $lcFormName='emailRetrieval-form';

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']===$lcFormName)
        {
            echo CActiveForm::validate($loModel);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['EmailRetrievalForm']))
        {
            $loModel->attributes=$_POST['EmailRetrievalForm'];
            // validate user input and redirect to the previous page if valid
            if($loModel->validate() && $loModel->register())
            {
                // Model is valid, build a user and user info based on the information we can gather
                //Yii::app()->getController()->redirect(Utilities::getCallbackURL());
                Yii::app()->getController()->redirect('/');
            }
        }
        $this->render('emailRetrieval', array('toModel'=>$loModel, 'tcFormName'=>$lcFormName));
    }
}

?>