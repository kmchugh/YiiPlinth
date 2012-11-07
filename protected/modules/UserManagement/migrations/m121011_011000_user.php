<?php
/**
 * Creates the User table
 */
class m121011_011000_user extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('{{User}}', array(
			'UserID'=>'pk',
			'GUID'=>'guid',
			'Email'=>'string',
			'DisplayName'=>'title',
			'Password'=>'code',
			'Locked'=>'boolean',
			'StartDate'=>'datetime',
			'EndDate'=>'datetime',
			'LoginCount'=>'integer',
			'LastLoginDate'=>'datetime_null',
			'Anonymous'=>'boolean',
			'CreatedDate'=>'datetime',
			'CreatedBy'=>'guid',
			'ModifiedDate'=>'datetime',
			'ModifiedBy'=>'guid',
			'RowVersion'=>'datetime',
			));

		$this->createIndex('UQ_{{User}}_GUID', "{{User}}", 'GUID', true);
		$this->createIndex('UQ_{{User}}_Email', "{{User}}", 'Email', true);
		$this->createIndex('UQ_{{User}}_DisplayName', "{{User}}", 'DisplayName', true);
	}

	public function safeDown()
	{
		$this->dropTable('{{User}}');
	}

}