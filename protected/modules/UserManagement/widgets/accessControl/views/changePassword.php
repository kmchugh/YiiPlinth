<?php
$loForm=$this->widget('HTMLForm', array(
    'id'=>$tcFormName,
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'containerClass'=>'changePassword',
    'title'=>Utilities::getString('Change Password'),
    'model'=>$toModel,
    'fields'=>array(
        array('type'=>'password','label'=>'password', 'placeholder'=>$toModel->getAttributeLabel('password'),),
        array('type'=>'password','label'=>'password_repeat', 'placeholder'=>$toModel->getAttributeLabel('password_repeat'),),
    ),
    'links'=>array(
        array('url'=>'/login', 'title'=>Utilities::getString("remembered password")),
    ),
    'buttons'=>array(
        array('type'=>'submit', 'title'=>Utilities::getString("Change")),
    ),
));
?>

