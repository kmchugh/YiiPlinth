<?php
$loForm=$this->widget('HTMLForm', array(
    'id'=>$tcFormName,
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'containerClass'=>'emailRetrieval',
    'title'=>Utilities::getString('Update Email'),
    'model'=>$toModel,
    'fields'=>array(
        array('type'=>'email',
                'label'=>'email',
                'hint'=>Utilities::getString('email retrieval explanation'),
                'placeholder'=>$toModel->getAttributeLabel('email'),),
        array('type'=>'email','label'=>'email_repeat', 'placeholder'=>$toModel->getAttributeLabel('email_repeat'),),
    ),
    'buttons'=>array(
        array('type'=>'submit', 'title'=>Utilities::getString("Connect")),
    ),
));
?>

