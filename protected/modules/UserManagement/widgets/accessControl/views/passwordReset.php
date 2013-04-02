<?php
$loForm=$this->widget('HTMLForm', array(
    'id'=>$tcFormName,
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'containerClass'=>'passwordReset',
    'title'=>Utilities::getString('Reset Password'),
    'model'=>$toModel,
    'fields'=>array(
        array('type'=>'email','label'=>'email', 'placeholder'=>Utilities::getString("What's your registered email?"),),
        array('type'=>'captcha','label'=>'verifyCode', 'placeholder'=>$toModel->getAttributeLabel('verifyCode'),),
    ),
    'links'=>array(
        array('url'=>'/login', 'title'=>Utilities::getString("remembered password")),
    ),
    'buttons'=>array(
        array('type'=>'submit', 'title'=>Utilities::getString("Retrieve Password")),
    ),
));
?>

