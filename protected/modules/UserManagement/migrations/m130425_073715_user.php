<?php
// Adds the SLUG colum to the user table for easy profile lookup and SEO URLS
class m130425_073715_user extends CDbMigration
{
	public function safeUp()
	{
        // Create the column
        $this->addColumn('{{User}}', 'Slug', 'guid_null');


        // We do this row by row so we can keep the migration code simple for multi platform
        $laUsers = $this->getDbConnection()->createCommand()
            ->select('UserID, DisplayName')
            ->from('{{User}}')->queryAll();

        foreach ($laUsers as $loOldUser)
        {
            // Generate the Slug
            $loUser = new User();
            $loUser->DisplayName = $loOldUser['DisplayName'];
            $lcSlug = $loUser->generateSlug();

            echo 'Updating Slug for '.$loUser->DisplayName.'['.$lcSlug."]\n";

            // Set the data
            $this->getDbConnection()->createCommand()
                ->update('{{User}}',
                    array('Slug'=>md5(strtolower($lcSlug))),
                    'UserID=:UserID',
                    array(':UserID'=>$loOldUser['UserID']
                    ));
        }

        // Alter the column to not allow nulls
        $this->alterColumn('{{User}}', 'Slug', 'guid');
	}

	public function safeDown()
	{
        $this->dropColumn('{{User}}','Slug');
	}
}
?>