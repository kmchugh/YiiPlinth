<?php
/**
 * Adds the featured column to the user info table
 */
class m121109_022537_userInfo extends CDbMigration
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