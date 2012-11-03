<?php
/**
 * Drops the NotifyUpdates column
 * Adds the CountryID column and relation and populates from Country
 * Drops Country
 */
class m121103_152000_userInfo extends CDbMigration
{

    private static $g_oCountryLookup;
    private static $g_aListofUserInfo;

    public function safeUp()
    {
        $this->dropColumn('{{UserInfo}}','NotifyUpdates');
        $this->addColumn('{{userInfo}}','CountryID','id_null');

        $this->addForeignKey('FK_{{UserInfo}}_CountryID', '{{UserInfo}}', 'CountryID',
                    '{{Country}}', 'CountryID', 'NO ACTION', 'NO ACTION');

        self::$g_aListofUserInfo = $this->getDbConnection()->createCommand()
                                                                                                            ->select('*')
                                                                                                            ->from('{{userInfo}}')->queryAll();


        //populate the CountryID of the UserInfo table
        foreach(self::$g_aListofUserInfo as $laUserInfo)
        {
            $lnCountryID = $this->getCountryID($laUserInfo['Country']);

            $this->update('{{userInfo}}', 
                array('CountryID'=>$lnCountryID,),
                'UserInfoID=:userInfoID',
                array(':userInfoID'=>$laUserInfo['UserInfoID'])
                );
        }

        self::$g_oCountryLookup = null;
        self::$g_aListofUserInfo = null;

        //now we can drop the country column
        $this->dropColumn('{{UserInfo}}','Country');

        //add the remaining columns
        $this->addColumn('{{UserInfo}}','BirthDay','integer_null');
        $this->addColumn('{{UserInfo}}','BirthMonth','integer_null');
        $this->addColumn('{{UserInfo}}','BirthYear','integer_null');
        $this->addColumn('{{UserInfo}}','GenderID','id_null');

         $this->addForeignKey('FK_{{UserInfo}}_GenderID', '{{UserInfo}}', 'GenderID',
                    '{{Gender}}', 'GenderID', 'NO ACTION', 'NO ACTION');
    }



    //this returns the countryID
    private function getCountryID($tcCountryName)
    {
        if (!self::$g_oCountryLookup)
        {
            self::$g_oCountryLookup = $this->getDbConnection()->createCommand()
            ->select('CountryID, Name')
            ->from('{{country}}')->queryAll();
        }

        foreach (self::$g_oCountryLookup as $laCountry)
        {
            if (strtoupper($laCountry['Name']) === $tcCountryName)
            {
                return $laCountry['CountryID'];
            }
        }
        return null;
    }

    private function getCountryName($tcCountryID)
    {
        if(!self::$g_oCountryLookup)
        {
            self::$g_oCountryLookup = $this->getDbConnection()->createCommand()
                                                                                                                    ->select('CountryID, Name')
                                                                                                                    ->from('{{country}}')->queryAll();
        }

        foreach (self::$g_oCountryLookup as $laCountry)
        {
            if($laCountry['CountryID'] === $tcCountryID)
            {
                return $laCountry['Name'];
            }
        }
        return null;
    }

    public function safeDown()
    {
        $this->dropColumn('{{UserInfo}}','NotifyUpdates','boolean');
        $this->addColumn('{{UserInfo}}','Country','string_null');

        self::$g_aListofUserInfo = $this->getDbConnection()->createCommand()
                                                                                                            ->select('*')
                                                                                                            ->from('{{userInfo}}')->queryAll();

        //populate the Country of the UserInfo table
        foreach(self::$g_aListofUserInfo as $laUserInfo)
        {
            $lcCountryName = $this->getCountryName($laUserInfo['CountryID']);

            $this->update('{{userInfo}}', 
                array('Country'=>$lnCountryName,),
                'UserInfoID=:userInfoID',
                array(':userInfoID'=>$laUserInfo['UserInfoID'])
                );
        }

        $this->dropColumn('{{UserInfo}}','CountryID');
        $this->dropColumn('{{UserInfo}}','BirthDay');
        $this->dropColumn('{{UserInfo}}','BirthMonth');
        $this->dropColumn('{{UserInfo}}','BirthYear');
        $this->dropColumn('{{UserInfo}}','GenderID');

    }

}