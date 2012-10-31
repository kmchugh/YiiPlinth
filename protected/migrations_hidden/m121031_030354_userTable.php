<?php

class m121031_030354_userTable extends CDbMigration
{
	public function safeUp()
	{
		$this->dropColumn('{{User}}','Anonymous');

	}

	public function safeDown()
	{
		$this->addColumn('{{User}}', 'Anonymous');
		// We do this row by row so we can keep the migration code simple for multi platform
		$laUsers = $this->getDbConnection()->createCommand()
			->select('UserID, DisplayName, GUID')
			->from('{{User}}')->queryAll();
		foreach($laUsers as $value)
		{
			$value=0;
		}
	}
}