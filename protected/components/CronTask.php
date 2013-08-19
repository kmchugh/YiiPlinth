<?php
/**
 * Class CronTask All commands for CronCommand should inherit from this class
 */
class CronTask
{
    /**
     * default constructor
     */
    function __construct()
    {
    }


    /**
     * Executes the cron task.  Should be overridden in each task
     */
    public function execute()
    {
        echo "\n".'Executing task: {'.get_class($this)."}\n";
    }
}
?>