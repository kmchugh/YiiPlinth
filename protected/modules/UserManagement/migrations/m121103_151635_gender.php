<?php

/**
 * Creates and populates the Gender Table
 */
class m121103_151635_gender extends CDbMigration
{
   public function safeUp()
    {
        $this->createTable('{{Gender}}', array(
            'GenderID'=>'pk',
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

        $this->createIndex('UQ_{{Gender}}_Code', "{{Gender}}", 'Code', true);
        $this->createIndex('IX_{{Gender}}_Sequence', "{{Gender}}", 'Sequence', false);

        $laGenders = $this->getGenderList();
        $lnTimestamp = Utilities::getTimestamp();
        $lcUser = 'SYSTEM';
        foreach ($laGenders as $laGender) 
        {
            echo $laGender['Name'];
            $this->insert('{{Gender}}', array(
                'Code'=>$laGender['Code'],
                'Name'=>$laGender['Name'],
                'Description'=>$laGender['Description'],
                'GUID'=>md5($laGender['Code']),
                'Sequence'=>$laGender['Sequence'],
                'ImageURI'=>$laGender['ImageURI'],
                'CreatedDate'=>$lnTimestamp,
                'CreatedBy'=>$lcUser,
                'ModifiedDate'=>$lnTimestamp,
                'ModifiedBy'=>$lcUser,
                'RowVersion'=>$lnTimestamp,
                ));
        }


    }

    public function safeDown()
    {
        $this->dropTable('{{Gender}}');
    }

    private function getGenderList()
    {
        return array(
            array('Code'=>'M',
                'Name'=>'Male',
                'Description'=>'Male',
                'Sequence'=>0,
                'ImageURI'=>NULL),
            array('Code'=>'F',
                'Name'=>'Female',
                'Description'=>'Female',
                'Sequence'=>1,
                'ImageURI'=>NULL),
            );
    }
}