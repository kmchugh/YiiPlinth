<?php
echo PlinthHTML::link(Utilities::getString('About'), '/site/page/view/about', null);
echo PlinthHTML::link(Utilities::getString('Contact Us'), '/site/contact', null);

// TODO: Don't write out the script if $tlLinkWindow is false
?>
<script>
    function loginConfig()
    {
        return {
            loadEvent : 'load',
            preventClickThrough : true
        };
    }
</script>
<?php
    if (Yii::app()->user->isGuest)
    {
        echo PlinthHTML::link(Utilities::getString('Sign in'), '/login', null);
        echo PlinthHTML::link(Utilities::getString('Register'), '/register', null);

    }
    else
    {
        $loUser = User::model()->findByPk(Yii::app()->user->getId());
        $lcDisplayName = $loUser->DisplayName;
        $lcProfileImage = $loUser->userInfo->getUserProfileURI();
        echo "<ul><li class=\"profile tiny\"><img src=\"{$lcProfileImage}\" alt=\"{$lcDisplayName}\"/>";
        echo PlinthHTML::link($lcDisplayName, array('/profile/view', 'guid'=>Yii::app()->user->GUID), null);
        echo '<ul><li>';
        echo PlinthHTML::link(Utilities::getString('Edit Profile'), array('/profile/update', 'guid'=>Yii::app()->user->GUID), null);
        echo '</li><li>';
        echo PlinthHTML::link(Utilities::getString('Logout'), '/logout', null);
        echo '</li></ul>';
        echo '</li></ul>';
    }
?>