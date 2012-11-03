<?php
/**
 * Adds the UserURL to the User Table
 */
class m121011_033449_userInfo extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		// Create the column
		$this->addColumn('{{UserInfo}}', 'UserURL', 'code_null');

		// We do this row by row so we can keep the migration code simple for multi platform
		$laUsers = $this->getDbConnection()->createCommand()
			->select('UserID, DisplayName, GUID')
			->from('{{User}}')->queryAll();

		foreach ($laUsers as $loUser)
		{
			$lcURL = strtolower(preg_replace("/[^A-Za-z0-9]/", '', $loUser['DisplayName']));
			// Check if this UserURL already exists
			if ($this->getDbConnection()->createCommand()
				->select('UserURL')
				->from('{{UserInfo}}')
				->where('UserURL=:UserURL', array(':UserURL'=>$lcURL))
				->queryRow())
			{
				$lcURL = $lcURL.$loUser['GUID'];
			}

			// Set the data
			$this->getDbConnection()->createCommand()
				->update('{{UserInfo}}',
				array('UserURL'=>$lcURL),
				'UserID=:UserID',
				array(
					':UserURL'=>$lcURL,
					':UserID'=>$loUser['UserID']
					));
		}

		// Alter the column to not allow nulls
		$this->alterColumn('{{UserInfo}}', 'UserURL', 'code');
	}

	public function safeDown()
	{
		$this->dropColumn('{{UserInfo}}', 'UserURL');
	}
}