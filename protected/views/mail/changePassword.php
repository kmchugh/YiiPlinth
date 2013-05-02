<h4>Hi <?php echo $userModel->DisplayName; ?>,</h4>
<p>Your password has successfully been changed.<br/><br/>
    If you did not initiate this change then you can reset your password by navigating to the link below or pasting the url into your address bar<br/>
    <strong><a href="<?php echo Yii::app()->createAbsoluteUrl('login'); ?>"><?php echo Yii::app()->createAbsoluteUrl('login'); ?></a></strong><br/>
</p>