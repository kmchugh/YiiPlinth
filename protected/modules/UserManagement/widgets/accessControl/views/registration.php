<div class="form row registration">
    <h1><?php echo Utilities::getString('Register_title'); ?></h1>
    
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
                        echo $form->labelEx($toModel,'email');
                        echo $form->textField($toModel,'email', array('placeholder'=>$toModel->getAttributeLabel('email')));
                        echo $form->error($toModel,'email');
                    ?>
                </div>

                <div class="field">
                    <?php
                        echo $form->labelEx($toModel,'email_repeat');
                        echo $form->textField($toModel,'email_repeat', array('placeholder'=>$toModel->getAttributeLabel('email_repeat')));
                        echo $form->error($toModel,'email_repeat');
                    ?>
                </div>

                <div class="field tandc">
                    <?php
                    echo $form->checkBox($toModel,'accept_terms',array('value' => 1, 'uncheckValue'=>0));
                    echo $form->labelEx($toModel,'accept_terms');
                    echo $form->error($toModel,'accept_terms');
                    ?>
                </div>
            </div>

            <div class="buttons">
                <?php
                 echo CHtml::htmlButton(Utilities::getString('Register'), array('type'=>'submit')); 
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
            <a href="/login"><?php echo Utilities::getString("signin_link"); ?></a>
        </div>

    <?php $this->endWidget(); ?>
</div>

