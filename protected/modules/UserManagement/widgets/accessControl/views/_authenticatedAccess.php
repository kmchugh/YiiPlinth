<ul>
    <li>
        <a href="<?php echo Yii::app()->controller->createURL('/userProfile/view/guid/'.Yii::app()->user->GUID); ?>">
            <img class="thumb" src="<?php echo Yii::app()->user->getProfileImageURI(); ?>"/>
            <span class="userProfile"><?php echo Yii::app()->user->DisplayName; ?></span>
        </a>
        <?php
            if (isset($this->submenu))
            {
                echo '<ul>';
                foreach ($this->submenu as $lcText => $lcLink) 
                {
                    echo '<li>';
                    echo PlinthHTML::link(Utilities::getString($lcText), $lcLink, array());
                    echo '</li>';
                }
                echo '</ul>';
            }
         ?>
    </li>
</ul>