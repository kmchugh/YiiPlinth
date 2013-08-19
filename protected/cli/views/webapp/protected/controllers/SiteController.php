<?php
/**
 * Class SiteController Default controller for the site
 */
class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: /site/page/view/FileName
            'page'=>array(
                'class'=>'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $this->render('index');
    }
}
?>