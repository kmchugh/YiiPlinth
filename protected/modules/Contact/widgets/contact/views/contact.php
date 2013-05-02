<?php
$loForm=$this->widget('HTMLForm', array(
    'id'=>$tcFormName,
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'containerClass'=>'contact',
    'title'=>Utilities::getString('Contact Us'),
    'model'=>$toModel,
    'fields'=>array(
        array('type'=>'text','label'=>'name', 'placeholder'=>$toModel->getAttributeLabel('name'),),
        array('type'=>'email','label'=>'email', 'placeholder'=>$toModel->getAttributeLabel('email'),),
        array('type'=>'text','label'=>'subject', 'placeholder'=>$toModel->getAttributeLabel('subject'),),
        array('type'=>'textarea','label'=>'body', 'placeholder'=>$toModel->getAttributeLabel('body'),),
        array('type'=>'captcha','label'=>'verifyCode', 'placeholder'=>$toModel->getAttributeLabel('verifyCode'),),
    ),
    'buttons'=>array(
        array('type'=>'submit', 'title'=>Utilities::getString("Submit")),
    ),
));
?>