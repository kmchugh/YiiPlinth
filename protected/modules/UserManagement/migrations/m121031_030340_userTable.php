<?php

class m121031_030340_userTable extends CDbMigration
{
	public function up()
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
			'LastLoginDate'=>'datetime',
			'Anonymous'=>'boolean',
			'CreatedDate'=>'datetime',
			'CreatedBy'=>'guid',
			'ModifiedDate'=>'datetime',
			'ModifiedBy'=>'guid',
			'RowVersion'=>'datetime',
			));
		$this->createIndex('UQ_{{User}}_GUID', "{{User}}", 'GUID', true);
		$this->createIndex('UQ_{{User}}_Email', "{{User}}", 'Email' true);

	}

	public function down()
	{
		$this->dropTable('{{User}}');
	}

}