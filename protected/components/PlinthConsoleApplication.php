<?php
/**
 * Base Application class used for overriding the Yii framework application where needed.
 */

class PlinthConsoleApplication extends CConsoleApplication
{
    private $m_oTheme;

    /**
     * @return CThemeManager the theme manager.
     */
    public function getThemeManager()
    {
        $loManager =  $this->getComponent('themeManager');
        $loManager->setBasePath(Yii::getPathOfAlias('application').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'themes');
        return $loManager;
    }

    /**
     * Returns the view renderer.
     * If this component is registered and enabled, the default
     * view rendering logic defined in {@link CBaseController} will
     * be replaced by this renderer.
     * @return IViewRenderer the view renderer.
     */
    public function getViewRenderer()
    {
        return $this->getComponent('viewRenderer');
    }

    /**
     * Returns the widget factory.
     * @return IWidgetFactory the widget factory
     * @since 1.1
     */
    public function getWidgetFactory()
    {
        return $this->getComponent('widgetFactory');
    }


    /**
     * @return CTheme the theme used currently. Null if no theme is being used.
     */
    public function getTheme()
    {
        if(is_string($this->m_oTheme))
        {
            $this->m_oTheme=$this->getThemeManager()->getTheme($this->m_oTheme);
        }
        return $this->m_oTheme;
    }

    /**
     * @param string $tcValue the theme name
     */
    public function setTheme($tcValue)
    {
        $this->m_oTheme=$tcValue;
    }

    /**
     * Registers the core application components.
     * This method overrides the parent implementation by registering additional core components.
     * @see setComponents
     */
    protected function registerCoreComponents()
    {
        parent::registerCoreComponents();

        $loComponents=array(
            'themeManager'=>array(
                'class'=>'CThemeManager',
            ),
            'widgetFactory'=>array(
                'class'=>'CWidgetFactory',
            ),
        );

        $this->setComponents($loComponents);
    }

}
?>