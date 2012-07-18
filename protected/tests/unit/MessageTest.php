<?php 
Yii::import('application.controllers.MessageController');
class MessageTest extends CTestCase
{
	public function testRepeat()
	{
		$loMessage = new MessageController("Test Message");
		$lcYell = "Hello?";
		$this->assertEquals($loMessage->repeat($lcYell), $lcYell);
	}
}

?>