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

    /**
     * Generates a HTML Button element
     * @param string $tcLabel the label for the button
     * @param null $tcType the type of the button, defaults to button
     * @param array $taHTMLOptions the HTML Options to attach to the button
     * @return string the renderable button string
     */
    public static function htmlButton($tcLabel='button',$tcType=NULL, $taHTMLOptions=array())
    {
        if(!isset($taHTMLOptions['name']))
        {
            $taHTMLOptions['name']=self::ID_PREFIX.self::$count++;
        }
        if (!is_null($tcType))
        {
            $taHTMLOptions['type']=$tcType;
        }
        if(!isset($taHTMLOptions['type']))
        {
            $taHTMLOptions['type']='button';
        }
        self::clientChange('click',$taHTMLOptions);
        return self::tag('button',$taHTMLOptions,$tcLabel);
    }

    /**
     * Generates a text field input for a model attribute.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $toModel the data model
     * @param string $tcAttribute the attribute
     * @param array $taHTMLOptions additional HTML attributes. Besides normal HTML attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated input field
     * @see clientChange
     * @see activeInputField
     */
    public static function activeTextField($toModel,$tcAttribute,$taHTMLOptions=array())
    {
        self::resolveNameID($toModel,$tcAttribute,$taHTMLOptions);
        self::clientChange('change',$taHTMLOptions);
        return self::activeInputField(isset($taHTMLOptions['type']) ? $taHTMLOptions['type'] : 'text',$toModel,$tcAttribute,$taHTMLOptions);
    }

    /**
     * Generates a text field input.
     * @param string $tcName the input name
     * @param string $tcValue the input value
     * @param array $taHTMLOptions additional HTML attributes. Besides normal HTML attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated input field
     * @see clientChange
     * @see inputField
     */
    public static function textField($tcName, $tcValue='',$taHTMLOptions=array())
    {
        self::clientChange('change',$taHTMLOptions);
        return self::inputField(isset($taHTMLOptions['type']) ? $taHTMLOptions['type'] : 'text',$tcName,$tcValue,$taHTMLOptions);
    }

    /**
     * Renders a ColourPalette, usually used on the visualTest pages
     * @param $tcColourName The name of the colour to render
     * @param $tcColour the colour value
     * @param array $taHTMLOptions any additional html options
     * @return string the renderable html
     */
    public static function colourPalette($tcColourName, $tcColour,$taHTMLOptions=array())
	{
		// Add the colour palette and colour to the options
		$taHTMLOptions['class'] = "colourPalette $tcColourName".(isset($taHTMLOptions['class']) ? ' '.$taHTMLOptions['class'] : '');

		$lcContent = '<span class="lighten">@'.$tcColourName.'_4</span><span class="lighter">@'.$tcColourName.'_3</span><span class="lightest">@'.$tcColourName.'_2</span><span class="white">@'.$tcColourName.'_1</span><span class="normal">@'.$tcColourName.' - #'.$tcColour.'</span><span class="darken">@'.$tcColourName.'_6</span><span class="darker">@'.$tcColourName.'_5</span>';

		return self::tag('div', $taHTMLOptions, $lcContent);
	}
}
