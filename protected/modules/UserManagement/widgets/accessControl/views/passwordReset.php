<div class="form row passwordReset">
	<h1><?php echo Utilities::getString('Reset Password'); ?></h1>
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
                        echo $form->textField($toModel,'email', array('placeholder'=>Utilities::getString("What's your registered email?")));
                        echo $form->error($toModel,'email');
                    ?>
                </div>

                <?php if(CCaptcha::checkRequirements()): ?>
                    <div class="field captcha">
                        <div class="image"><?php $this->widget('CCaptcha',
                                array('buttonOptions'=>array('title'=>Utilities::getString('Get a new code')))); ?></div>
                        <div class="hint"><?php echo Utilities::getString('Enter the letters above') ?></div>
                        <div class="field">
                            <?php
                                echo $form->labelEx($toModel,'verifyCode');
                                echo $form->textField($toModel,'verifyCode');
                                echo $form->error($toModel,'verifyCode');
                            ?>
                        </div>
                        <div class="hint"><?php echo Utilities::getString('Letters are not case-sensitive') ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="buttons">
				<?php echo CHtml::htmlButton(Utilities::getString('Retrieve Password'), array('type'=>'submit')); ?>
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