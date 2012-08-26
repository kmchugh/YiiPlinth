<?php
/**
 * Creates the User and UserInfo tables.  This is enough for user registration and login
 * @return [type] [description]
 */
class m120824_134421_UserManagement extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('{{User}}', array(
			'UserID'=>'pk',
			'GUID'=>'guid',
			'Email'=>'string',
			'DisplayName'=>'title',
			'Password'=>'varchar(32) NOT NULL',
			'Locked'=>'boolean',
			'StartDate'=>'datetime_null',
			'EndDate'=>'datetime_null',
			'LoginCount'=>'integer',
			'LastLoginDate'=>'datetime_null',
			'CreatedDate'=>'datetime',
			'CreatedBy'=>'guid',
			'ModifiedDate'=>'datetime',
			'ModifiedBy'=>'guid',
			'Rowversion'=>'datetime',
			));
		$this->createIndex('UQ_{{User}}_GUID', "{{User}}", 'GUID', true);

		$this->createTable('{{UserInfo}}', array(
			'UserInfoID'=>'pk',
			'UserID'=>'id',
			'NotifyUpdates'=>'boolean',
			'Country'=>'string_null',
			'ProfileImageURI'=>'uri_null',
			'FirstName'=>'string',
			'LastName'=>'string_null',
			'Description'=>'text',
			'CreatedDate'=>'datetime',
			'CreatedBy'=>'guid',
			'ModifiedDate'=>'datetime',
			'ModifiedBy'=>'guid',
			'Rowversion'=>'datetime',
			));
		$this->addForeignKey('FK_{{UserInfo}}_UserID', '{{UserInfo}}', 'UserID',
					'{{User}}', 'UserID', 'NO ACTION', 'NO ACTION');
	}

	public function safeDown()
	{
		$this->dropTable('{{UserInfo}}');
		$this->dropTable('{{User}}');
	}
}
