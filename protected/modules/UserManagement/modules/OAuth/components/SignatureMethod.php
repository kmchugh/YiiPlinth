<?php
abstract class SignatureMethod
{
	public abstract function getName();
	public abstract function sign($tcMethod, $tcURL, $toParameters, $tcSecret, $tcTokenSecret = '');

}

?>