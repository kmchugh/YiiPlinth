<?php

class m121030_083540_test_run extends CDbMigration
{
	public function safeUp()
	{
		$this->dropColumn('{{User}}','Anonymous');

	}

	public function safeDown()
	{
		$this->addColumn('{{School}}', 'Anonymous');
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