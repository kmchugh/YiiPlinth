<div class="form row forgottenPassword">
    <h1><?php echo Utilities::getString('Email Retrieval'); ?></h1>
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>$tcFormName,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

        <fieldset>
            <p class="note"><?php echo Utilities::getString('Fields with'); ?> <span class="required">*</span> <?php echo Utilities::getString('are required'); ?></p>

            <div class="fields">
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
            </div>

            <div class="buttons">
                <?php
                 echo CHtml::htmlButton(Utilities::getString('Update'), array('type'=>'submit')); 
                 ?>
            </div>
            <div class="field">
                <label class="note">
                    <?php echo Utilities::getString('email retrieval explanation'); ?>
            </label>

            <?php if(Yii::app()->user->hasFlash('formMessage')): ?> 
                <div class="flash-success">
                    <?php echo Yii::app()->user->getFlash('formMessage'); ?>
                </div>
            <?php endif; ?>

        </fieldset>

<?php $this->endWidget(); ?>
</div>

