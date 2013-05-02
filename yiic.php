<?php

$lcYiiDirectory = NULL;
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YIIPLINTH_FRAMEWORK') or define('YIIPLINTH_FRAMEWORK', dirname(__FILE__).DIRECTORY_SEPARATOR);

// Extract the Required Folders
echo "Discovering Yii Folder:  ";

$lcPath =  dirname(dirname(__FILE__));
$laDirectories = scandir($lcPath);
foreach ($laDirectories as $lcDir)
{
    if (is_dir($lcDir) && $lcDir !== '.' && $lcDir !=='..' && preg_match('/^yii-.+/i',$lcDir))
    {
        $lcYiiDirectory = file_exists($lcPath.DIRECTORY_SEPARATOR.$lcDir.DIRECTORY_SEPARATOR.'framework')? $lcPath.DIRECTORY_SEPARATOR.$lcDir.DIRECTORY_SEPARATOR : NULL;
        echo $lcYiiDirectory."\n";
        defined('YII_FRAMEWORK') or define('YII_FRAMEWORK', $lcYiiDirectory);
    }
}

if (is_null($lcYiiDirectory))
{
    throw new Exception("Yii Framework Directory not found in [$lcPath]");
}

// We know where Yii is, so time to get going.
require_once($lcYiiDirectory.'framework/yii.php');

// Include the Utilities always
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'protected'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'Utilities.php');

$loApp=Yii::createConsoleApplication(array('basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'protected'.DIRECTORY_SEPARATOR.'cli'));
$loApp->run();

?>