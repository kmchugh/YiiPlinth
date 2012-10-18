<?php
abstract class SignatureMethod
{
	public abstract function getName();
	public abstract function sign($tcMethod, $tcURL, $toParameters, $tcSecret, $tcTokenSecret = '');

	protected function urlencode($tcInput) 
	{
		return (is_scalar($tcInput)) ?
			str_replace('+',' ',str_replace('%7E', '~', rawurlencode($tcInput))) :
			'';
	}
}

?>