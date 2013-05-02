<?php
    $loForm=$this->widget('HTMLForm', array(
    'id'=>$tcFormName,
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'containerClass'=>'login',
    'title'=>Utilities::getString('Sign In'),
    'model'=>$toModel,
    'oauth'=>true,
    'fields'=>array(
        array('type'=>'email','label'=>'username', 'placeholder'=>$toModel->getAttributeLabel('username'),),
        array('type'=>'password','label'=>'password', 'placeholder'=>$toModel->getAttributeLabel('password'),),
        array('type'=>'link','label'=>Utilities::getString("Forgot your password"), 'url'=>'PasswordReset', 'class'=>'linkField'),
    ),
    'links'=>array(
        array('url'=>'/register', 'title'=>Utilities::getString("register_link")),
    ),
    'buttons'=>array(
        array('type'=>'submit', 'title'=>Utilities::getString("Sign in")),
    ),
));
?>

