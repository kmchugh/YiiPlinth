<?php
class WebAppCommand extends CConsoleCommand
{
    private $m_cApplicationPath;

    public function getHelp()
    {
        return <<<EOD
USAGE
  yiic webapp <app-path> [<vcs>]

DESCRIPTION
  This command generates an YiiPlinth Web Application at the specified location.

PARAMETERS
 * app-path: required, the directory where the new application will be created.
   If the directory does not exist, it will be created. After the application
   is created, please make sure the directory can be accessed by Web users.
EOD;
    }

    public function run($taArgs)
    {
        if (!isset($taArgs[0]))
        {
            $this->usageError('The web application location is not set');
        }
        $this->m_cApplicationPath = '.'.DIRECTORY_SEPARATOR.strtr($taArgs[0], '/\\', DIRECTORY_SEPARATOR);

        if (file_exists($this->m_cApplicationPath))
        {
            $this->usageError("The directory [$this->m_cApplicationPath] already exists");
        }

        if ($this->confirm("Create the Web Application at [{$this->m_cApplicationPath}]?"))
        {
            $lcTemplate = $this->getTemplateDir();
            if ($lcTemplate===false)
            {
                die("\nTemplate directory does not exist\n");
            }

            // Get the list of files to process
            $laList = $this->buildFileList($lcTemplate, $this->m_cApplicationPath, '', array(), array());
            // Prepare callback for files needing updates
            $this->addFileModificationCallbacks($laList);
            // Copy the Files
            $this->copyFiles($laList);
            // Finally update the permissions
            //$this->setPermissions($lcAppPath);
        }
    }

    private function getTemplateDir()
    {
        return realpath(dirname(__FILE__).'/../views/webapp');
    }

    private function addfileModificationCallbacks(&$taFileList)
    {
        $taFileList['index.php']['callback']=array($this,'generateIndex');
        $taFileList['protected/yiic.php']['callback']=array($this,'generateYiic');
        $taFileList['protected/config/common.php']['callback']=array($this,'generateCommonConfig');

        // TODO: Add a testing bootstrap

    }

    private function setPermissions($tcAppDirectory)
    {

    }

    public function generateIndex($tcSource, $taParams)
    {
        $lcContent=file_get_contents($tcSource);
        // Get the relative directory of the bootstrap from the application directory
        $lcBootstrap = Utilities::getRelativePath($this->m_cApplicationPath, YIIPLINTH_FRAMEWORK.'protected'.DIRECTORY_SEPARATOR.'bootstrap.php');

        // Get the relative directory of yiic from the application directory
        $lcYii = Utilities::getRelativePath($this->m_cApplicationPath, YII_FRAMEWORK.'framework'.DIRECTORY_SEPARATOR.'yii.php');

        // Replace the directories
        $lcContent = preg_replace('/YIIC_BOOTSTRAP/', $lcBootstrap, $lcContent);
        $lcContent = preg_replace('/YII_PHP/', $lcYii, $lcContent);
        return $lcContent;
    }

    public function generateYiic($tcSource, $taParams)
    {
        $lcContent=file_get_contents($tcSource);
        // Get the relative directory of the bootstrap from the application directory
        $lcBootstrap = Utilities::getRelativePath($this->m_cApplicationPath, YIIPLINTH_FRAMEWORK.'protected'.DIRECTORY_SEPARATOR.'bootstrap.php');

        // Get the relative directory of yiic from the application directory
        $lcYii = Utilities::getRelativePath($this->m_cApplicationPath, YII_FRAMEWORK.'framework'.DIRECTORY_SEPARATOR.'yii.php');

        // Replace the directories
        $lcContent = preg_replace('/YIIC_BOOTSTRAP/', $lcBootstrap, $lcContent);
        $lcContent = preg_replace('/YII_PHP/', $lcYii, $lcContent);
        return $lcContent;
    }

    public function generateCommonConfig($tcSource, $taParams)
    {
        $lcContent=file_get_contents($tcSource);
        // Extract the App Name from the path
        $lcAppName = basename($this->m_cApplicationPath);

        // Configure the DB
        if ($this->confirm("Configure the database?"))
        {
            $lcInput = $this->prompt("Enter your connection string (mysql:host=127.0.0.1;dbname=mydb):");
            if ($lcInput !== false)
            {
                $lcContent = preg_replace('/CONNECTION_STRING/', $lcInput, $lcContent);

                $lcInput = $this->prompt("Enter the database username for your application:");
                if ($lcInput !== false)
                {
                    $lcContent = preg_replace('/DB_USER/', $lcInput, $lcContent);

                    $lcInput = $this->prompt("Enter the database password for your application:");
                    if ($lcInput !== false)
                    {
                        $lcContent = preg_replace('/DB_PASSWORD/', $lcInput, $lcContent);
                    }
                }
            }
        }

        // Replace the tokens
        $lcContent = preg_replace('/APPLICATION_NAME/', $lcAppName, $lcContent);
        return $lcContent;
    }

}
?>