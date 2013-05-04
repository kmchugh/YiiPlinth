<?php
class EmailPush extends CronTask
{
    public function execute()
    {
        require_once(Yii::getPathOfAlias('YIIPlinth.extensions.Email.Mail').DIRECTORY_SEPARATOR.'YiiMailMessage.php');
        require_once(Yii::getPathOfAlias('YIIPlinth.extensions.Email.Mail').DIRECTORY_SEPARATOR.'YiiMail.php');

        // Retrieve the emails from the EmailStore table and send them out
        // This is done one at a time so that other processess could also send out the emails if needed
        while(!is_null($loEmail = MailStore::model()->find('MailStoreID > 0')))
        {
            if ($loEmail->delete())
            {
                // Send the user an email with a link to change password
                $loMessage = new YiiMailMessage;
                $loMessage->view = $loEmail->View;
                $loMessage->layout = $loEmail->Layout;
                $loMessage->setBody(unserialize($loEmail->Parameters), 'text/html');
                $loMessage->subject = $loEmail->Subject;
                $loMessage->addTo($loEmail->To);
                $loMessage->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminName']));
                Yii::app()->mail->send($loMessage);
            }
        }
    }
}
?>