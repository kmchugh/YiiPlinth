<?php

/**
 * Creates and populates the Change Password Token table
 */
class m121103_151800_changePasswordToken extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('{{ChangePasswordToken}}', array(
            'ChangePasswordToken'=>'pk',
            'UserGUID'=>'guid',
            'Token'=>'guid',
            'Expires'=>'datetime',
            'CreatedDate'=>'datetime',
            'CreatedBy'=>'guid',
            'ModifiedDate'=>'datetime',
            'ModifiedBy'=>'guid',
            'Rowversion'=>'datetime',
        ));

        $this->createIndex('IX_{{ChangePasswordToken}}_Token', "{{ChangePasswordToken}}", 'Token', false);
        $this->createIndex('IX_{{ChangePasswordToken}}_Expires', "{{ChangePasswordToken}}", 'Expires', false);
    }

    public function safeDown()
    {
        $this->dropTable('{{ChangePasswordToken}}');
    }
}