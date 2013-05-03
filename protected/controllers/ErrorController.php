<?php
// TODO: Log the errors that occur if they are not already being logged
class ErrorController extends Controller
{
    /**
     * Renders the error view based on the error number, order of loading is
     * - Specific number (i.e. error_404)
     * - Group number (i.e. error_4__)
     * - default (i.e. error)
     */
    public function actionError()
    {
        $loError=Yii::app()->errorHandler->error;
        if (!is_null($loError))
        {
            $lnCode = $loError['code'];
            $lcTemplate = $this->getViewFile('error_'.strval($lnCode));
            if (empty($lcTemplate))
            {
                $lcTemplate = $this->getViewFile('error_'.substr(strval($lnCode), 0, 1).'__');
                if (empty($lcTemplate))
                {
                    $lcTemplate = $this->getViewFile('error');
                }
            }
            $lcTemplate = basename($lcTemplate, '.php');
            $this->render($lcTemplate, array('toError'=>$loError));
        }
        else
        {
            $this->redirect('/');
        }
    }
}
?>