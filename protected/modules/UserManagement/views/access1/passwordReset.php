<?php
$this->pageTitle=Yii::app()->name . ' - Reset Password';
?>

<h1>Reset Password</h1>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>$tcFormName,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<fieldset>
		<p class="note">Fields with <span class="required">*</span> are required.</p>

		<div>
			<?php echo $form->labelEx($toModel,'email'); ?>
			<?php echo $form->textField($toModel,'email'); ?>
			<?php echo $form->error($toModel,'email'); ?>
		</div>

		<?php if(CCaptcha::checkRequirements()): ?>
			<div>
				<div><?php $this->widget('CCaptcha'); ?></div>
				<div>
					<?php echo $form->labelEx($toModel,'verifyCode'); ?>
					<?php echo $form->textField($toModel,'verifyCode'); ?>
				</div>
				<div class="note">Please enter the letters as they are shown in the image above.
				<br/>Letters are not case-sensitive.</div>
				<?php echo $form->error($toModel,'verifyCode'); ?>
			</div>
		<?php endif; ?>

		<div class="buttons">
			<?php echo CHtml::htmlButton('Reset', array('type'=>'submit')); ?>
		</div>

		<?php if(Yii::app()->user->hasFlash('formMessage')): ?> 
			<div class="flash-success">
			    <?php echo Yii::app()->user->getFlash('formMessage'); ?>
			</div>
		<?php endif; ?>

	</fieldset>

<?php $this->endWidget(); ?>

