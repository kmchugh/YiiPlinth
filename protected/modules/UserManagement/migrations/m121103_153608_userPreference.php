<?php
/**
 * Creates the UserPreferenceType table and the UserPreference Table
 */
class m121103_153608_userPreference extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('{{UserPreferenceType}}', array(
            'UserPreferenceTypeID'=>'pk',
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

        $this->createIndex('UQ_{{UserPreferenceType}}_Code', "{{UserPreferenceType}}", 'Code', true);
        $this->createIndex('IX_{{UserPreferenceType}}_Sequence', "{{UserPreferenceType}}", 'Sequence', false);


        $this->createTable('{{UserPreference}}', array(
            'UserPreferenceID'=>'pk',
            'UserID'=>'id_null',
            'GroupID'=>'id_null',
            'TypeID'=>'id',
            'IntValue'=>'integer_null',
            'DateValue'=>'datetime_null',
            'FloatValue'=>'float_null',
            'StringValue'=>'long_string_null',
            'BooleanValue'=>'boolean_null',
            'CreatedDate'=>'datetime',
            'CreatedBy'=>'guid',
            'ModifiedDate'=>'datetime',
            'ModifiedBy'=>'guid',
            'Rowversion'=>'datetime',
            ));

        $this->addForeignKey('FK_{{UserPreference}}_TypeID', '{{UserPreference}}', 'TypeID', 
                            '{{UserPreferenceType}}', 'UserPreferenceTypeID','NO ACTION', 'NO ACTION');

        $this->addForeignKey('FK_{{UserPreference}}_UserID', '{{UserPreference}}', 'UserID', 
                            '{{User}}', 'UserID','NO ACTION', 'NO ACTION');

        $this->addForeignKey('FK_{{UserPreference}}_GroupID', '{{UserPreference}}', 'GroupID', 
                            '{{Group}}', 'GroupID','NO ACTION', 'NO ACTION');


        $this->createIndex('IX_{{UserPreference}}_UserID', "{{UserPreference}}", 'UserID', false);
        $this->createIndex('IX_{{UserPreference}}_GroupID', "{{UserPreference}}", 'GroupID', false);
        $this->createIndex('IX_{{UserPreference}}_TypeID', "{{UserPreference}}", 'TypeID', false);
        $this->createIndex('IX_{{UserPreference}}_UserID_TypeID', "{{UserPreference}}", 'UserID, TypeID', false);
        $this->createIndex('IX_{{UserPreference}}_GroupID_Type', "{{UserPreference}}", 'GroupID, TypeID', false);

    }

    public function safeDown()
    {

        $this->dropTable('{{UserPreference}}');
        $this->dropTable('{{UserPreferenceType}}');
    }

}