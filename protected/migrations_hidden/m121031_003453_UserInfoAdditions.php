<?php

class m121031_003453_UserInfoAdditions extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('{{UserInfo}}','CountryID');
		$this->addColumn('{{UserInfo}}','BirthDay');
		$this->addColumn('{{UserInfo}}','BirthMonth');
		$this->addColumn('{{UserInfo}}','BirthYear');
		$this->addColumn('{{UserInfo}}','GenderID');
		// We do this row by row so we can keep the migration code simple for multi platform
		$laUsers = $this->getDbConnection()->createCommand()
			->select('UserID, DisplayName, GUID')
			->from('{{User}}')->queryAll();
	}

	public function safeDown()
	{
		$this->dropColumn('{{UserInfo}}','CountryID');
		$this->dropColumn('{{UserInfo}}','BirthDay');
		$this->dropColumn('{{UserInfo}}','BirthMonth');
		$this->dropColumn('{{UserInfo}}','BirthYear');
		$this->dropColumn('{{UserInfo}}','GenderID');
	}

}