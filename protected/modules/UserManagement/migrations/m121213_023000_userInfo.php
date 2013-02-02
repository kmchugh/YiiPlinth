<?php
/**
 * Creates the unique constraint to the UserID in UserINfo
 */
class m121213_023000_userInfo extends CDbMigration
{
    public function safeUp()
    {
        $this->createIndex('UQ_{{UserInfo}}_UserID', "{{UserInfo}}", 'UserID', true);
    }

    public function safeDown()
    {
        $this->dropIndex('UQ_{{UserInfo}}_UserID', "{{UserInfo}}");
    }

}