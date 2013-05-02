<?php

return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
        'basePath'=>dirname(__FILE__).'/..',
        'components'=>array(
            'fixture'=>array(
                'class'=>'system.test.CDbFixtureManager',
            ),
            'db'=>array(
                'connectionString' => 'mysql:host=127.0.0.1;dbname=test1',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => null,
                'charset' => 'utf8',
            ),
        ),
    )
);
