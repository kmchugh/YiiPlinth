<?php

/**
 * Adds a few more colums in the Group table and creates the GroupTypeID table
 */

class m121213_023019_group extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('{{Group}}','ProfileImageURI','uri_null');
		$this->addColumn('{{Group}}','GroupURL','uri_null');
		$this->addColumn('{{Group}}','GroupTypeID','id');

		$this->createTable('{{GroupType}}', array(
			'GroupTypeID'=>'pk',
			'Code'=>'code',
			'Name'=>'title',
			'Description'=>'description',
			'GUID'=>'guid',
			'Sequence'=>'integer',
			'ImageURI'=>'uri_null',
			'CreatedDate'=>'datetime',
			'CreatedBy'=>'guid',
			'ModifiedDate'=>'datetime',
			'ModifiedBy'=>'guid',
			'Rowversion'=>'datetime',
			));

		$this->addForeignKey('FK_{{GroupType}}_GroupTypeID', '{{GroupType}}', 'GroupTypeID',
							'{{Group}}', 'GroupTypeID', 'NO ACTION', 'NO ACTION');

	}

	public function safeDown()
	{
		
		$this->dropTable('{{GroupTypeID}}');
		$this->dropColumn('{{Group}}','ProfileImageURI');
		$this->dropColumn('{{Group}}','GroupURL');
		$this->dropColumn('{{Group}}','GroupTypeID');
	}

}