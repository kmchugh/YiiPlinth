<?php

// change the following paths if necessary
$yiit=dirname(dirname(dirname(dirname(__FILE__)))).'/yii-1.1.10.r3566/framework/yiit.php';
require_once($yiit);

// Setup an alias for YIIPlinth
YiiBase::setPathOfAlias('YIIPlinth', dirname(dirname(__FILE__)));
$config=dirname(dirname(__FILE__)).'/config/test.php';

require_once(dirname(__FILE__).'/WebTestCase.php');
Yii::createWebApplication($config);
?>