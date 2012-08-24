<?php
$this->pageTitle=Yii::app()->name . ' - Register';
?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>$tcFormName,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <fieldset>
        <legend><h1>Register</h1></legend>
        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <div class="field">
            <?php echo $form->labelEx($toModel,'email'); ?>
            <?php echo $form->textField($toModel,'email'); ?>
            <?php echo $form->error($toModel,'email'); ?>
        </div>

        <div class="field">
            <?php echo $form->labelEx($toModel,'email_repeat'); ?>
            <?php echo $form->textField($toModel,'email_repeat'); ?>
            <?php echo $form->error($toModel,'email_repeat'); ?>
        </div>
        
        <div class="buttons">
            <?php
             echo CHtml::htmlButton('Register', array('type'=>'submit')); 
             ?>
        </div>
        <div class="field">
            <label class="note">
                Clicking 'Register' means that you agree to the<a target="_blank" href="/site/TandC">terms of service</a> and <a href="/site/Privacy" target="_blank">privacy statement</a>.
        </label>

        <?php if(Yii::app()->user->hasFlash('formMessage')): ?> 
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('formMessage'); ?>
            </div>
        <?php endif; ?>

    </fieldset>

<?php $this->endWidget(); ?>

