<?php 
    $lcLoginURL = "/login";
    $lcRegisterURL = "/register";

    if (!Utilities::isCurrentURL($lcLoginURL))
    {
	    echo PlinthHTML::link(
	        Utilities::getString('Sign in'), $lcLoginURL,
	        array('dromos-module'=>'ajaxLink/dromos.ajaxLink'));
    }

    if (!Utilities::isCurrentURL($lcRegisterURL))
    {
    	echo PlinthHTML::link(
        	Utilities::getString('Register'), $lcRegisterURL,
        	array());
    }
     
?>
