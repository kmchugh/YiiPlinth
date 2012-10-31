<?php

class m121031_001546_UserInfoDeletions extends CDbMigration
{
	public function safeUp()
	{
		$this->dropColumn('{{UserInfo}}','NotifyUpdates');
		$this->dropColumn('{{UserInfo'}},'Country');
	}

	public function safeDown()
	{
		$this->addColumn('{{UserInfo}}','NotifyUpdates');
		$this->addColumn('{{UserInfo}}','Country');
	
	}

}