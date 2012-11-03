<?php

class DefaultController extends Controller
{
    /**
     * Default action for retrieving a .less file, this will parse the .less file and serve a .css file instead.
     * @param  String $path the path of the .less file
     * @param  String $file the name of the .less file, not including the .less extension
     */
    public function actionIndex($path)
    {
        // Check if the file exists
        $lcFile = Yii::getPathOfAlias('YIIPlinth').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$path;

        if (file_exists($lcFile))
        {
            header("Pragma: public"); // required
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false); // required for certain browsers 
            header("Content-Type:  " . CFileHelper::getMimeType($lcFile));
            header("Content-Disposition: attachment; filename=\"" . str_replace(" ", "-", preg_replace("@[^a-z0-9 ]@", "", strtolower($lcFile))) .'.'.CFileHelper::getExtension($lcFile)."\";" );
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".filesize($lcFile));
            readfile($lcFile);
            exit();
        }
        else
        {
            throw new CHttpException(404,'The requested resource does not exist.');
        }
    }
}