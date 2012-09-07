<?php
class PlinthHTML extends CHtml
{
	/**
	 * Generates a hyperlink tag.  The tag will have the class active if the tag href is equal to the url being served
	 * @param string $text link body. It will NOT be HTML-encoded. Therefore you can pass in HTML code such as an image tag.
	 * @param mixed $url a URL or an action route that can be used to create a URL.
	 * See {@link normalizeUrl} for more details about how to specify this parameter.
	 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
	 * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
	 * @return string the generated hyperlink
	 * @see normalizeUrl
	 * @see clientChange
	 */
	public static function link($tcText,$tcURL='#',$taHTMLOptions=array())
	{
		if (Utilities::isCurrentURL($tcURL))
		{
			$taHTMLOptions['class'] = isset($taHTMLOptions['class']) ? $taHTMLOptions['class'].' active' : 'active';
		}
		return parent::link($tcText, $tcURL, $taHTMLOptions);
	}
}
