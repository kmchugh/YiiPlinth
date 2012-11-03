<div class="OAuthProviders">
<?php
    foreach ($OAuthProviders as $lcName => $lcURL) 
    {
        echo PlinthHTML::link($lcName, $lcURL, array('class'=>$lcName));
    }
?>
</div>