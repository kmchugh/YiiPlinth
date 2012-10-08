<?php
class LayoutMapManager
{
    public $map = array();

    // TODO: Remove the static map after refactor
    public static $g_oMap = array();

    public function init()
    {
        self::$g_oMap = $this->map;
    }

    public static function getLayout($toController)
    {
        $lcRoute = $toController->getRoute();
        $laMapItem = isset(self::$g_oMap[$lcRoute]) ? self::$g_oMap[$lcRoute] : $toController->layout;
        if (is_array($laMapItem))
        {
            if (isset($laMapItem['style']))
            {
                if (!is_array($laMapItem['style']))
                {
                    $laMapItem['style'] = array($laMapItem['style']);
                }
                foreach ($laMapItem['style'] as $lcCSS)
                {
                    Yii::app()->clientScript->registerCssFile($lcCSS);
                }
            }

            $laMapItem = isset($laMapItem['layout']) ? $laMapItem['layout'] : $toController->layout;
        }
        return $laMapItem;
    }
}
?>