<?php
/**
 * Class CronCommand is a maintenance command that should be executed by the systems cron %
 * every minute.  CronCommand will then execute tasks every 15 seconds for 1 minute and exit.
 */
class CronCommand extends CConsoleCommand
{
    /**
     * Displays the user help
     * @return string
     */
    public function getHelp()
    {
        return <<<EOD
USAGE
  yiic cron <counter>
  yiic cron start
  yiic cron stop

DESCRIPTION
  Executes the maintenance command.  Each time the maintenance command is executed
  a counter increments specifying which sequence to execute.
  The command will look at the contents of the commands/cron/ folder and will attempt to execute all of the
  CronTasks within a sequence.

  A sequence is determined by using the mod function with the sequence and the name of the folders found in the
  commands/cron folder

  For example:
  commands/cron/2       // Tasks within this folder would be executed every 2nd execution
  commands/cron/4       // Tasks within this folder would be executed every 4th execution
  commands/cron/15      // Tasks within this folder would be executed every 15th execution

  To determine the timing of an execution sequence, multiply the system cron schedule by

PARAMETERS
 * counter: optional, if provided designates which maintenance sequence is run.
    Should be an integer between 0 and 3600 representing each second in a day if the
    cron job is executed every second.

 * start: sets up the cron task for the system

 * stop: removes the cron task for the system

EOD;
    }

    /**
     * Executes the command and kicks off the appropriate tasks
     * A counter is incremented each time the task is executed and the
     * tasks executed depend on the value of the counter
     * @param array $taArgs arguments passed to the task
     */
    public function run($taArgs)
    {
        $llStoreCounter = false;
        $lnLoopCounter = 0;
        $lcKey='MAINTENANCE_COUNTER';
        if (count($taArgs)==1)
        {
            $lnCounter = $taArgs[0];
            if (!is_numeric($lnCounter))
            {
                if ($taArgs[0]==='start')
                {
                    $this->createCronTask();
                    return;
                }
                else if ($taArgs[0]==='stop')
                {
                    $this->destroyCronTask();
                    return;
                }
                else
                {
                    $this->usageError("counter is not a number!");
                }
            }
            $lnLoopCounter=3;
        }
        else
        {
            $lnCounter = Yii::app()->cache->get($lcKey);
            $lnCounter = $lnCounter === false ? 0 : ($lnCounter % 3600);
            $llStoreCounter = true;
        }

        for ($i=$lnLoopCounter;$i<4;$i++)
        {
            $this->execute($lnCounter++);
            if ($llStoreCounter)
            {
                $lnCounter++;
                Yii::app()->cache->set($lcKey, $lnCounter);
            }
            if ($i != 3)
            {
                sleep(15);
            }
        }
    }

    /**
     * Gets the directory to be used for CronTab Jobs
     */
    private function getJobsDirectory()
    {
        $lcReturn = Yii::getPathOfAlias('application.crontabs').'/';
        if (!is_dir($lcReturn))
        {
            mkdir($lcReturn, 0777,true);
        }
        return $lcReturn;
    }

    private function createCronTask()
    {
        // TODO: implement this for Windows as well
        echo 'Creating Cron Task'."\n";
        // Make the crontab directory if it does not exist

        $loCron = new Crontab('AppTasks', $this->getJobsDirectory());

        $loCron->addApplicationJob('yiic', 'cron', array(), '*/1');
        $loCron->saveCronFile();
        $loCron->saveToCrontab();

    }

    private function destroyCronTask()
    {
        // TODO: implement this for Windows as well
        echo 'Removing Cron Task'."\n";

        $loCron = new Crontab('AppTasks', $this->getJobsDirectory());
        $loCron->eraseJobs();
        $loCron->saveCronFile();
        $loCron->saveToCrontab();
    }

    /**
     * Executes the commands from the specified folder if they exist
     * @param $tnCounter the sequence to execute
     */
    private function execute($tnCounter)
    {
        // Check both YiiPlinth and custom app.
        $this->executeDirectory(Yii::getPathOfAlias('YIIPlinth.cli.commands.cron'), $tnCounter);
        $this->executeDirectory(Yii::getPathOfAlias('application.commands.cron'), $tnCounter);
    }

    /**
     * Determines which directories should be executed
     * @param $tcPath the path to search
     * @param $tnCounter the current counter
     */
    private function executeDirectory($tcPath, $tnCounter)
    {
        if (is_dir($tcPath))
        {
            // Extract which cron sequence to execute
            $laFiles = scandir($tcPath);
            foreach ($laFiles as $lcFile)
            {
                $lcDirectory = $tcPath.DIRECTORY_SEPARATOR.$lcFile;
                if (is_dir($lcDirectory) && is_numeric($lcFile))
                {
                    if (intval($lcFile) % ($tnCounter+1) == 0)
                    {
                        // Need to execute this directory
                        $this->executeCommands($lcDirectory);
                    }
                }
            }
        }
    }

    /**
     * Extracts and executes the CronTask commands in the specified directory
     * @param $tcPath the directory to extract the files from
     */
    private function executeCommands($tcPath)
    {
        if (is_dir($tcPath))
        {
            $laFiles = scandir($tcPath);
            foreach ($laFiles as $lcFile)
            {
                if (preg_match('/\.php$/', $lcFile))
                {
                    $lcClassName = basename($lcFile, '.php');
                    $lcFileName = $tcPath.DIRECTORY_SEPARATOR.$lcFile;
                    require_once($lcFileName);
                    $loTask = new $lcClassName();
                    if (!is_subclass_of($loTask, 'CronTask'))
                    {
                        $this->usageError("Task [$lcFileName] does not extend CronTask");
                    }
                    $loTask->execute();
                }
            }
        }
    }
}
?>