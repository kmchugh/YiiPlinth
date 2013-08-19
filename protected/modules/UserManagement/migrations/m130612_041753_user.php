<?php
/*
 * Class m130612_041753_user updates the user slugs to be more descriptive
 */
class m130612_041753_user extends CDbMigration
{
    public function safeUp()
    {
        // We do this row by row so we can keep the migration code simple for multi platform
        $laRows = $this->getDbConnection()->createCommand()
            ->select('UserID, User.DisplayName')
            ->from('{{User}}')->queryAll();

        foreach ($laRows as $loOldRows)
        {
            // Generate the Slug
            $loItem = new User();
            $loItem->DisplayName = $loOldRows['DisplayName'];
            $lcSlug = $loItem->generateSlug();

            echo 'Updating Slug for '.$loItem->DisplayName.'['.$lcSlug."]\n";

            // Set the data
            $this->getDbConnection()->createCommand()
                ->update('{{User}}',
                    array('Slug'=>strtolower($lcSlug)),
                    'UserID=:UserID',
                    array(':UserID'=>$loOldRows['UserID']
                    ));
        }
    }

    public function safeDown()
    {
        return true;

    }
}