<?php

/**
 * Creates the Membership table
 */
class m121109_061132_membership extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('{{Membership}}', array(
			'MembershipID'=>'pk',
			'ParentGroupID'=>'id',
			'MemberGroupID'=>'id_null',
			'MemberUserID'=>'id_null',
			'HashTag'=>'code',
			'Owner'=>'boolean',
			'Title'=>'title',
			'StartDate'=>'datetime',
            'EndDate'=>'datetime',
            'CreatedDate'=>'datetime',
            'CreatedBy'=>'guid',
            'ModifiedDate'=>'datetime',
            'ModifiedBy'=>'guid',
            'RowVersion'=>'datetime',
			));

		$this->createIndex('IX_{{Membership}}_ParentGroupID', '{{Membership}}', 'ParentGroupID', false);
		$this->createIndex('IX_{{Membership}}_MemberGroupID', '{{Membership}}', 'MemberGroupID', false);
		$this->createindex('IX_{{Membership}}_MemberUserID', '{{Membership}}', 'MemberUserID', false);

		$this->addForeignKey('FK_{{Membership}}_MemberUserID', '{{Membership}}', 'MemberUserID', 
                            '{{User}}', 'UserID', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('FK_{{Membership}}MemberGroupID', '{{Membership}}', 'MemberGroupID', 
                            '{{User}}', 'UserID', 'NO ACTION', 'NO ACTION');
		$this->addForeignKey('FK_{{Membership}}ParentGroupID', '{{Membership}}', 'ParentGroupID', 
                            '{{User}}', 'UserID', 'NO ACTION', 'NO ACTION');
	}

	public function safeDown()
	{
		$this->dropTable('{{Membership}}');
	}

}