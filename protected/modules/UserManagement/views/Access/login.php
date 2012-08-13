<?php
$this->pageTitle=Yii::app()->name . ' - Login';
?>

<h1>Login</h1>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<style type="text/css">
	.forgottenPassword, .rememberMe
	{
		margin: 1em 0 .5em 0;
	}
</style>

	<fieldset>
		<p class="note">Fields with <span class="required">*</span> are required.</p>

		<div>
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $form->textField($model,'username'); ?>
			<?php echo $form->error($model,'username'); ?>
			<p class="hint">
				Hint: Your user name is your email address.
			</p>
		</div>

		<div>
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password'); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>

		<div class="forgottenPassword">
			<?php echo CHtml::link("Forgot your password?",array('PasswordReset')) ?>
		</div>

		<div class="buttons">
			<?php
			 echo CHtml::htmlButton('Login', array('type'=>'submit')); 
			 ?>
		</div>

		<?php if(Yii::app()->user->hasFlash('formMessage')): ?> 
			<div class="flash-success">
			    <?php echo Yii::app()->user->getFlash('formMessage'); ?>
			</div>
		<?php endif; ?>

	</fieldset>

<?php $this->endWidget(); ?>

