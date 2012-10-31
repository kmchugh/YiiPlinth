<?php

class m121031_030354_users extends CDbMigration
{
	public function safeUp()
	{
		$this->dropColumn('{{User}}','Anonymous');

	}

	public function safeDown()
	{
		$this->addColumn('{{User}}', 'Anonymous');

		$this->update('{{User}}', array(
			'Anonymous'=>':Anonymous'
			),
			'',
			array(
			':Anonymous'=>0
			));
	}
}