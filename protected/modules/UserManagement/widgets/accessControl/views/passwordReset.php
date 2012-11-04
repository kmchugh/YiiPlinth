<div class="form row">
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

			<div class="field">
				<?php echo $form->labelEx($toModel,'email'); ?>
				<?php echo $form->textField($toModel,'email'); ?>
				<?php echo $form->error($toModel,'email'); ?>
			</div>

			<?php if(CCaptcha::checkRequirements()): ?>
				<div class="field captcha">
					<div><?php $this->widget('CCaptcha'); ?></div>
					<div class="field">
						<?php echo $form->labelEx($toModel,'verifyCode'); ?>
						<?php echo $form->textField($toModel,'verifyCode'); ?>
						<?php echo $form->error($toModel,'verifyCode'); ?>
						<div class="hint">Please enter the letters as they are shown in the image above.
						<br/>Letters are not case-sensitive.</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="buttons">
				<?php echo CHtml::htmlButton(Utilities::getString('Reset'), array('type'=>'submit')); ?>
			</div>

			<?php if(Yii::app()->user->hasFlash('formMessage')): ?> 
				<div class="flash-success">
				    <?php echo Yii::app()->user->getFlash('formMessage'); ?>
				</div>
			<?php endif; ?>

		</fieldset>
	<?php $this->endWidget(); ?>
</div>