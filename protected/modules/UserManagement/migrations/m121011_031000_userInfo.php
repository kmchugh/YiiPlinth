<?php

/**
 * Creates the UserInfo table
 */
class m121011_031000_userInfo extends CDbMigration
{
    public function safeUp()
    {
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
            'RowVersion'=>'datetime',
            ));

        $this->addForeignKey('FK_{{UserInfo}}_UserID', '{{UserInfo}}', 'UserID',
                    '{{User}}', 'UserID', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        $this->dropTable('{{UserInfo}}');
    }

}