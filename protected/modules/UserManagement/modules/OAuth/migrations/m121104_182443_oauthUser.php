<?php
/**
 * Adds the Verified field to OAuthUser
 */
class m121104_182443_oauthUser extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('{{OAuthUser}}','Verified','boolean');
        $this->addColumn('{{OAuthUser}}','Expires','datetime');

        $this->update('{{OAuthUser}}', 
            array('Verified'=>true),
            'UID IS NOT NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('{{OAuthUser}}', 'Verified');
        $this->dropColumn('{{OAuthUser}}', 'Expires');
    }

}