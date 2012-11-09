<?php
/**
 * Creates the Group table
 */
class m121103_153444_group extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('{{Group}}', array(
            'GroupID'=>'pk',
            'GUID'=>'guid',
            'Name'=>'title',
            'Description'=>'description',
            'StartDate'=>'datetime',
            'EndDate'=>'datetime',
            'CreatedDate'=>'datetime',
            'CreatedBy'=>'guid',
            'ModifiedDate'=>'datetime',
            'ModifiedBy'=>'guid',
            'RowVersion'=>'datetime',
            ));

        $this->createIndex('UQ_{{Group}}_GUID', '{{Group}}', 'GUID', true);
        $this->createIndex('UQ_{{Group}}_Name', '{{Group}}', 'Name', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{Group}}');
    }

}