<div class="oauthProviders">
<?php
    foreach ($OAuthProviders as $lcName => $lcURL) 
    {
        echo PlinthHTML::link($lcName, $lcURL, array('class'=>lcfirst($lcName)));
    }
?>
</div>