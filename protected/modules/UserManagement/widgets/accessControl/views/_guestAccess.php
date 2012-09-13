<?php 
    $lcLoginURL = "/login";
    $lcRegisterURL = "/register";

    if (!Utilities::isCurrentURL($lcLoginURL))
    {
	    echo PlinthHTML::link(
	        Utilities::getString('Sign in'), $lcLoginURL,
	        array('dromos-module'=>'ajaxlink/dromos.ajaxlink'));
    }

    if (!Utilities::isCurrentURL($lcRegisterURL))
    {
    	echo PlinthHTML::link(
        	Utilities::getString('Register'), $lcRegisterURL,
        	array('dromos-module'=>'ajaxlink/dromos.ajaxlink'));
    }
     
?>
