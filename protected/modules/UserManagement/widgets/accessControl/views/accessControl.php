<?php
    $this->render(Yii::app()->user->isGuest ?
            '_guestAccess' : '_authenticatedAccess');
?>