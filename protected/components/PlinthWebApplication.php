<?php
/**
 * Base Application class used for overriding the Yii framework application where needed.
 */

class PlinthWebApplication extends CWebApplication
{
/*
 *
 *  // TODO: Check if this should be implemented
    protected function registerCoreComponents()
    {
        parent::registerCoreComponents();

        $loComponents=array(
            'session'=>array(
                'class'=>'CHttpSession',
            ),
            'assetManager'=>array(
                'class'=>'CAssetManager',
            ),
            'user'=>array(
                'class'=>'CWebUser',
            ),
            'themeManager'=>array(
                'class'=>'CThemeManager',
            ),
            'authManager'=>array(
                'class'=>'CPhpAuthManager',
            ),
            'clientScript'=>array(
                'class'=>'CClientScript',
            ),
            'widgetFactory'=>array(
                'class'=>'CWidgetFactory',
            ),
        );

        $this->setComponents($components);
    }

*/

    /**
     * Creates a controller instance based on a route.
     * The route should contain the controller ID and the action ID.
     * It may also contain additional GET variables. All these must be concatenated together with slashes.
     *
     * This method will attempt to create a controller in the following order:
     * <ol>
     * <li>If the first segment is found in {@link controllerMap}, the corresponding
     * controller configuration will be used to create the controller</li>
     * <li>If the first segment is found to be a module ID, the corresponding module
     * will be used to create the controller;</li>
     * <li>Otherwise, it will search under the {@link controllerPath} and YiiPlinth controller path to create
     * the corresponding controller. For example, if the route is "admin/user/create",
     * then the controller will be created using the class file "protected/controllers/admin/UserController.php".</li>
     * </ol>
     * @param string $route the route of the request.
     * @param CWebModule $owner the module that the new controller will belong to. Defaults to null, meaning the application
     * instance is the owner.
     * @return array the controller instance and the action ID. Null if the controller class does not exist or the route is invalid.
     */
    public function createController($tcRoute,$toOwner=null)
    {
        if($toOwner===null)
        {
            $toOwner=$this;
        }

        if(($tcRoute=trim($tcRoute,'/'))==='')
        {
            $tcRoute=$toOwner->defaultController;
        }
        $tcRoute.='/';
        $lcControllerID='';

        while(($lnPos=strpos($tcRoute,'/'))!==false)
        {
            $lcID=!$this->getUrlManager()->caseSensitive ?
                strtolower(substr($tcRoute,0,$lnPos)) :
                substr($tcRoute,0,$lnPos);
            if(!preg_match('/^\w+$/',$lcID))
            {
                return null;
            }
            $tcRoute=(string)substr($tcRoute,$lnPos+1);

            if(!isset($lcBasePath))  // first segment
            {
                if(isset($toOwner->controllerMap[$lcID]))
                {
                    return array(
                        Yii::createComponent($toOwner->controllerMap[$lcID],$lcID,$toOwner===$this?null:$toOwner),
                        $this->parseActionParams($tcRoute),
                    );
                }

                if(($loModule=$toOwner->getModule($lcID))!==null)
                {
                    return $this->createController($tcRoute,$loModule);
                }

                $lcBasePath=$toOwner->getControllerPath();
                $lcControllerID='';
            }
            else
            {
                $lcControllerID.='/';
            }

            $lcClassName=ucfirst($lcID).'Controller';
            $lcClassFile=is_file($lcBasePath.DIRECTORY_SEPARATOR.$lcClassName.'.php') ?
                $lcBasePath.DIRECTORY_SEPARATOR.$lcClassName.'.php' :
                Yii::getPathOfAlias('YIIPlinth.controllers').DIRECTORY_SEPARATOR.$lcClassName.'.php'
                ;

            if(is_file($lcClassFile))
            {
                if(!class_exists($lcClassName,false))
                {
                    require($lcClassFile);
                }
                if(class_exists($lcClassName,false) && is_subclass_of($lcClassName,'CController'))
                {
                    $lcID[0]=strtolower($lcID[0]);
                    return array(
                        new $lcClassName($lcControllerID.$lcID,$toOwner===$this?null:$toOwner),
                        $this->parseActionParams($tcRoute),
                    );
                }
                return null;
            }
            $lcControllerID.=$lcID;
            $lcBasePath.=DIRECTORY_SEPARATOR.$lcID;
        }
    }

}

?>