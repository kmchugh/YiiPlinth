<?php
/**
 * Class m130504_021353_mailStore creates the MailStore table for storage of unsent emails
 * Emails will be processed and sent by the daemon
 */
class m130504_021353_mailStore extends CDbMigration
{
	public function safeUp()
	{
        $this->createTable('{{MailStore}}', array(
            'MailStoreID'=>'pk',
            'View'=>'string',
            'Layout'=>'string',
            'Subject'=>'string',
            'To'=>'long_string',
            'From'=>'code_null',
            'Parameters'=>'text',
            'Hash'=>'code',
            'CreatedDate'=>'datetime',
            'CreatedBy'=>'guid',
            'ModifiedDate'=>'datetime',
            'ModifiedBy'=>'guid',
            'Rowversion'=>'datetime',
        ));

        $this->createIndex('UQ_{{MailStore}}_Hash', "{{MailStore}}", 'Hash', true);
	}

	public function safeDown()
	{
        $this->dropTable('{{CMSEntry}}');
		return true;
	}
}