<?php

class DefaultController extends OAuthController
{
	protected function createOAuth()
	{
		return new Twitter();
	}
}