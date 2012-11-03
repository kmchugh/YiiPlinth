<div class="form row">
    <h1><?php echo Utilities::getString('Register'); ?></h1>
    
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>$tcFormName,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

        <fieldset>
            <p class="note"><?php echo Utilities::getString('Fields with'); ?> <span class="required">*</span> <?php echo Utilities::getString('are required'); ?></p>

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
                 echo CHtml::htmlButton(Utilities::getString('Register'), array('type'=>'submit')); 
                 ?>
            </div>
            <div class="field">
                <label class="note">
                    <?php echo Utilities::getString('registration agreement'); ?>
            </label>

            <?php if(Yii::app()->user->hasFlash('formMessage')): ?> 
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('formMessage'); ?>
                </div>
            <?php endif; ?>

        </fieldset>

    <?php $this->endWidget(); ?>
</div>

