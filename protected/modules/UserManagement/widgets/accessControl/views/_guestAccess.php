<?php 

    echo Chtml::link(
        //Utilities::getString('Sign in'), '/login',
        Utilities::getString('Sign in'), '/login',
        array('dromos-module'=>'ajaxLink/dromos.ajaxLink'));

    echo Chtml::link(
        Utilities::getString('Register'), '/register',
        array());
?>
