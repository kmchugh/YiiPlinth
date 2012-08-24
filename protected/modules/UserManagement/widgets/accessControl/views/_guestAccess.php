<?php 

    echo Chtml::link(
        'Sign in', '/login',
        array());

    echo Chtml::link(
        'Register', '/register',
        array());
/*

    if (Yii::app()->user()-isGuest)
    {

    }
    else
    {

    }
    array('label'=>'sign in', 
                        'url'=>array('/login'),
                        'visible'=>Yii::app()->user->isGuest),
                array('label'=>Yii::app()->user->getState('DisplayName'),
                        'template'=>'<div class="thumbMenu"><img class="thumb" src="'.$lcUserProfile.'"/>{menu}</div>',
                        'items'=>array(
                            array('label'=>'profile', 'url'=>'/userProfile/update/guid/'.Yii::app()->user->getState('GUID')),
                            array('label'=>'my streams', 'url'=>'/stream/index'),
                            array('label'=>'sign out', 'url'=>'/logout'),
                            ), 
                        'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'register', 
                        'url'=>array('/site/registration'),
                        'visible'=>Yii::app()->user->isGuest),
                         */

?>
