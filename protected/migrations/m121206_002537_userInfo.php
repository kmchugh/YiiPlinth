<?php
/**
 * Adds the featured column to the UserInfo
 */
class m121206_002537_userInfo extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('{{UserInfo}}','Featured','boolean');
	}

	public function safeDown()
	{
		$this->dropColumn('{{UserInfo}}','Featured');
	}
}