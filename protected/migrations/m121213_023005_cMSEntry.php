<?php
/**
 * Creates the CMSEntryType table and the CMSEntry Table
 */
class m121213_023005_cMSEntry extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('{{CMSEntryType}}', array(
            'CMSEntryTypeID'=>'pk',
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

        $this->createIndex('UQ_{{CMSEntryType}}_Code', "{{CMSEntryType}}", 'Code', true);
        $this->createIndex('IX_{{CMSEntryType}}_Sequence', "{{CMSEntryType}}", 'Sequence', false);


        $this->createTable('{{CMSEntry}}', array(
            'CMSEntryID'=>'pk',
            'TypeID'=>'id',
            'Section'=>'string',
            'Title'=>'string',
            'Sequence'=>'integer',
            'Text'=>'text',
            'CreatedDate'=>'datetime',
            'CreatedBy'=>'guid',
            'ModifiedDate'=>'datetime',
            'ModifiedBy'=>'guid',
            'Rowversion'=>'datetime',
        ));

        $this->addForeignKey('FK_{{CMSEntry}}_TypeID', '{{CMSEntry}}', 'TypeID',
            '{{CMSEntryType}}', 'CMSEntryTypeID','NO ACTION', 'NO ACTION');

        $this->createIndex('IX_{{UserPreference}}_TypeID', "{{CMSEntry}}", 'TypeID', false);

    }

    public function safeDown()
    {
        $this->dropTable('{{CMSEntry}}');
        $this->dropTable('{{CMSEntryType}}');
    }

}