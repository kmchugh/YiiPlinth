<h4>Hi <?php echo $userModel->DisplayName; ?>,</h4>
<p><?php echo Yii::app()->name;?> has received a request to reset the password for your account.
    To finish resetting your password click the link below, or paste the URL into your address bar.<br/>
    <strong><a href="<?php echo $resetURL; ?>"><?php echo $resetURL; ?></a></strong><br/>
</p>