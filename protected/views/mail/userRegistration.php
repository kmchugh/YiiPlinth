<h4>Welcome to <?php echo Yii::app()->name;?>, <?php echo $userModel->DisplayName; ?>.</h4>
<p>
    You have successfully signed up for a <?php echo Yii::app()->name;?> account.  In order to finalise your account
    we need to verify your email address.
</p>

<p>
    Click on the link below, or paste the url into your address bar to verify your email.<br/>
    <strong><a href="<?php echo $resetURL; ?>"><?php echo $resetURL; ?></a></strong>
</p>