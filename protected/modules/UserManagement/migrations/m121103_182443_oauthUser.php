<?php
/**
 * Creates the User table
 */
class m121103_182443_oauthUser extends CDbMigration
{
	public function up()
	{
		$this->createTable('{{OAuthUser}}', array(
			'OAuthUserID'=>'pk',
			'UserID'=>'id_null',
			'UserGUID'=>'guid',
			'Provider'=>'code',
			'UID'=>'text',
			'Token'=>'text',
			'Secret'=>'text',
			'DisplayName'=>'text',
			'UserName'=>'text',
			'CreatedDate'=>'datetime',
			'CreatedBy'=>'guid',
			'ModifiedDate'=>'datetime',
			'ModifiedBy'=>'guid',
			'Rowversion'=>'datetime',
			));

		$this->addForeignKey('FK_{{OAuthUser}}_UserID', '{{OAuthUser}}', 'UserID',
                    		'{{User}}', 'UserID', 'NO ACTION', 'NO ACTION');
	}

	public function down()
	{
		$this->dropTable('{{OAuthUser}}');
	}

}