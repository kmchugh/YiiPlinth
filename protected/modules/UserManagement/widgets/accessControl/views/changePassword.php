<div class="form changePassword">
    <h1><?php echo Utilities::getString('Change Password'); ?></h1>
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
                <?php
                echo $form->labelEx($toModel,'password');
                echo $form->passwordField($toModel,'password', array('placeholder'=>$toModel->getAttributeLabel('password')));
                echo $form->error($toModel,'password');
                ?>
            </div>

            <div class="field">
                <?php
                echo $form->labelEx($toModel,'password_repeat');
                echo $form->passwordField($toModel,'password_repeat', array('placeholder'=>$toModel->getAttributeLabel('password_repeat')));
                echo $form->error($toModel,'password_repeat');
                ?>
            </div>
        </div>

        <div class="buttons">
            <?php echo CHtml::htmlButton(Utilities::getString('Change'), array('type'=>'submit')); ?>
        </div>

        <?php if(Yii::app()->user->hasFlash('formMessage')): ?>
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('formMessage'); ?>
            </div>
        <?php endif; ?>

        <div class="links">
            <a href="/login"><?php echo Utilities::getString("remembered password"); ?></a>
        </div>

    </fieldset>
    <?php $this->endWidget(); ?>
</div>