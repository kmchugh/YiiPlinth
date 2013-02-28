<?php
/**
 * The LayoutMapManager allows the specification of the theme, layout and style for pages based on their
 * route
 */
class LayoutMapManager
{

    /**
     * The map is an associative array mapping routes to rules for theme, layout, and style.
     * The map works as chained rules meaning that each matching rule will be applied in order of precedence.
     * / would be applied first, then /route, then /route/anotherRoute.  This allows for overriding values.
     * As an example of overriding values, the default theme for / might be classic, the style might be customStyle and 
     * the layout may be primaryPage.  
     * The /mobile rule may have a value for the style of customMobile.  This will mean that any user browsing
     * to a URL that starts with /mobile will receive the following settings:
     * layout => primaryPage
     * style => customMobile
     * theme => classic
     *
     * An example rule map:
     * 'layoutMap'=>array(
     * // application components
     * 'components'=>array(
     *     'class'=>'YIIPlinth.components.LayoutMapManager',
     *     'map'=>array(
     *         '/'=>array(
     *             'layout'=>function(){return Yii::app()->isGuest ? '//layouts/primaryPage' : '//layouts/authenticatedPage';},
     *             'style'=>function(){return Yii::app()->isGuest ? 'home.less' : 'secondaryDefault.less';}
     *             'theme'=>'classic'
     *          ),
     *          '/UserManagement/'=>array('layout'=>'//layouts/primaryPage', 'style'=>'userManagement.less'),
     *          '/Site/'=>array('layout'=>'//layouts/primaryPage', 'style'=>'/infoPage.less', 'theme'=>'classic'),
     *      ),
     *  ),
     */
    public $map = array();

    /**
     * Initialises the LayoutMapManager
     */
    public function init()
    {
        // Sort the Map for ease of navigation
        uksort($this->map, function($toA, $toB)
            {
                return strlen($toA)-strlen($toB);
            });
    }

    /**
     * Extracts the current route from the specified controller
     * @param  CController $toController The controller that we are calculating the layout for
     * @param  CAction $tcAction     The Action that is currently being executed
     * @return String               The route for this controller
     */
    private function extractRoute($toController, $tcAction)
    {
        $loModule = $toController->getModule();
        $lcReturn = $toController->id.'/'.(is_string($tcAction) ? $tcAction : $tcAction->id);
        while(!is_null($loModule))
        {
            $lcReturn = $loModule->id.'/'.$lcReturn;
            $loModule = $loModule->parentModule;
        }
        return '/'.$lcReturn;
    }

    /**
     * Extracts the value for the layout, theme, or style
     * @param  mixed        A string of function that returns a string
     * @return string       the string value to be used as a layout, theme, or style
     */
    private function evaluate($toValue)
    {
        return is_callable($toValue) ? $toValue() : $toValue;
    }

    /**
     * Applies the layout, theme, and style to the specified controller
     * @param  CController $toController The controller to apply the styling to
     * @param  CAction $tcAction     The action that is being executed on toController
     */
    public function applyLayout($toController, $tcAction)
    {
        $lcRoute = $this->extractRoute($toController, $tcAction);
        $lcStyle = NULL;
        foreach ($this->map as $lcKey => $loValue)
        {
            if (Utilities::startsWith($lcRoute, $lcKey, true))
            {
                // Apply the rules at this level
                if (isset($loValue['style']))
                {
                    $lcStyle = $this->evaluate($loValue['style']);
                }

                if (isset($loValue['layout']))
                {
                    $toController->layout = $this->evaluate($loValue['layout']);
                }

                if (isset($loValue['theme']))
                {
                    $lcTheme = $this->evaluate($loValue['theme']);
                    Yii::app()->theme = $lcTheme;
                    Yii::app()->session['theme'] = $lcTheme;
                }
            }
        }

        // Apply the determined style
        if (!is_null($lcStyle))
        {
            Yii::app()->clientScript->registerCssFile($lcStyle);
        }
    }
}
?>