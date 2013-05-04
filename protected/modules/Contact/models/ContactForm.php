<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            // name, email, subject and body are required
            array('name, email, subject, body', 'required'),
            // email has to be a valid email address
            array('email', 'email'),
            // verifyCode needs to be entered correctly
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'name'=>'Name',
            'email'=>'Email',
            'subject'=>'Subject',
            'body'=>'Body',
            'verifyCode'=>'Verification Code',
        );
    }

    /**
     * Sends the actual contact information
     */
    public function sendContact()
    {
        if (!$this->hasErrors())
        {
            $loEmail = new PlinthMail(Yii::app()->params['adminEmail'],
                $this->subject,
                array('tcContent'=>"Name: {$this->name}.  Email: {$this->email}<br/><br/>".$this->body),
                '//mail/defaultEmail',
                '//layouts/mail'
            );
            $loEmail->send();
        }
        return !$this->hasErrors();
    }
}