<div class="form row login">
    <h1><?php echo Utilities::getString('Sign In'); ?></h1>

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
                        echo $form->labelEx($toModel,'username');
                        echo $form->textField($toModel,'username', array('placeholder'=>$toModel->getAttributeLabel('username')));
                        echo $form->error($toModel,'username');
                    ?>
                </div>

                <div class="field">
                    <?php
                        echo $form->labelEx($toModel,'password');
                        echo $form->passwordField($toModel,'password', array('placeholder'=>$toModel->getAttributeLabel('password')));
                        echo $form->error($toModel,'password');
                    ?>
                </div>

                <div class="field">
                    <?php echo CHtml::link(Utilities::getString("Forgot your password?"),array('PasswordReset')) ?>
                </div>
            </div>

                <div class="buttons">
                    <?php
                     echo CHtml::htmlButton(Utilities::getString('Sign in'), array('type'=>'submit'));
                     ?>
                </div>

                <?php if(Yii::app()->user->hasFlash('formMessage')): ?>
                    <div class="flash-success">
                        <?php echo Yii::app()->user->getFlash('formMessage'); ?>
                    </div>
                <?php endif; ?>
        </fieldset>

        <div class="oauth">
            <label><?php echo Utilities::getString("or"); ?></label>
            <span class="oauth"/>
        </div>

        <div class="links">
            <a href="/register"><?php echo Utilities::getString("register_link"); ?></a>
        </div>

    <?php $this->endWidget(); ?>
</div>

