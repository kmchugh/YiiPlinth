<?php
class SignatureHMACSHA1 extends SignatureMethod
{
	public function getName()
	{
		return 'HMAC-SHA1';
	}

	public function sign($tcMethod, $tcURL, $toParameters, $tcSecret, $tcTokenSecret = '')
	{
		$lcBase = strtoupper($tcMethod).'&'.rawurlencode($tcURL).'&';
		$lcParameter = '';
		$loParameters = array();

		foreach ($toParameters as $lcKey => $lcValue) 
		{
			$loParameters[rawurlencode($lcKey)] = rawurlencode($lcValue);
		}
		ksort($loParameters);

		foreach ($loParameters as $lcKey => $lcValue) 
		{
			$lcParameter.=$lcKey.'='.$lcValue.'&';
		}
		$lcParameter = rawurlencode(substr($lcParameter, 0, strlen($lcParameter)-1));

		$loParameters['oauth_signature']=rawurlencode(base64_encode(hash_hmac('sha1', $lcBase.$lcParameter, $tcSecret.'&'.$tcTokenSecret, true)));

		ksort($loParameters);
		return $loParameters;
	}
}
?>
