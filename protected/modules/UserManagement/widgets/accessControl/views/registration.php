<?php
$loForm=$this->widget('HTMLForm', array(
    'id'=>$tcFormName,
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'containerClass'=>'registration',
    'title'=>Utilities::getString('Register_title'),
    'model'=>$toModel,
    'oauth'=>true,
    'fields'=>array(
        array('type'=>'email','label'=>'email', 'placeholder'=>$toModel->getAttributeLabel('email'),),
        array('type'=>'email','label'=>'email_repeat', 'placeholder'=>$toModel->getAttributeLabel('email_repeat'),),
        array('type'=>'checkbox','label'=>'accept_terms', 'class'=>'tandc'),
    ),
    'links'=>array(
        array('url'=>'/login', 'title'=>Utilities::getString("signin_link")),
    ),
    'buttons'=>array(
        array('type'=>'submit', 'title'=>Utilities::getString("Sign in")),
    ),
));
?>

