<?php
/**
 * Removal of the anonymous field from the User table
 */
class m121011_012000_user extends CDbMigration
{
	public function safeUp()
	{
		$this->dropColumn('{{User}}','Anonymous');
	}

	public function safeDown()
	{
		$this->addColumn('{{User}}', 'Anonymous');

		$this->update('{{User}}', 
			array(
				'Anonymous'=>':Anonymous'
			),
			'',
			array(':Anonymous'=>0)
		);
	}
}