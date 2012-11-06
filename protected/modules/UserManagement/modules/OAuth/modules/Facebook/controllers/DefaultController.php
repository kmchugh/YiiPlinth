<?php
// TODO: Refactor with Twitter Controller
class DefaultController extends OAuthController
{
	protected function createOAuth()
	{
		return new Facebook();
	}
}