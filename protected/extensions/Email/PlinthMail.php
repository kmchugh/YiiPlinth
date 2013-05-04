<?php
class PlinthMail
{
    private $m_oTo;
    private $m_cSubject;
    private $m_aParameters;
    private $m_cView;
    private $m_cLayout;

    /**
     * Creates a new mail message to be sent
     * @param $toRecipient The email address(es) of the user to send the email to, multiple addresses should be in an array()
     * @param $tcSubject The subject line for the email
     * @param array $taParameters The parameters for the email
     * @param string $tcView the view to use to render the email
     * @param string $tcLayout the layout to use to render the email
     */
    function __construct($toRecipient, $tcSubject, $taParameters = array(), $tcView = '//mail/default', $tcLayout = '//layouts/mail')
    {
        $this->m_aParameters=$taParameters;
        $this->m_cLayout=$tcLayout;
        $this->m_cSubject=$tcSubject;
        $this->m_cView=$tcView;
        $this->m_oTo=$toRecipient;
    }


    /**
     * Stores the Email so that it can be sent at a later time
     * @return bool if the email content stored successfully
     */
    public function send()
    {
        $this->m_oTo = is_array($this->m_oTo) ? $this->m_oTo : array($this->m_oTo);

        // Each email will be sent separately to protect privacy
        foreach($this->m_oTo as $lcRecipient)
        {
            $loMail = new MailStore();
            $loMail->From = Yii::app()->user->isGuest ? 'SYSTEM' : Yii::app()->user->GUID;
            $loMail->Layout = $this->m_cLayout;
            $loMail->View = $this->m_cView;
            $loMail->Subject = $this->m_cSubject;
            $loMail->To = $lcRecipient;
            $loMail->Parameters=serialize($this->m_aParameters);

            if (!$loMail->save())
            {
                return false;
            }
        }
        return true;
    }

}
?>