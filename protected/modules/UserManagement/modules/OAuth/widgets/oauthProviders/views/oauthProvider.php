<div class="oauthProviders">
<?php
    foreach ($OAuthProviders as $lcName => $lcURL) 
    {
        echo PlinthHTML::link(Utilities::getString('oauth_link_'.$lcName), $lcURL, array('class'=>lcfirst($lcName)));
    }
?>
</div>