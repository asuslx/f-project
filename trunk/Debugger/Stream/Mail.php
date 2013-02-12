<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 1/26/13
 */
 
class F_Debugger_Stream_Mail  extends F_Debugger_Stream {

    private $_emails;
    private $_from;
    private $_replyTo;

    public function __construct(array $emails, $from = 'F_Debugger', $replyTo = "noreply@example.com") {

        $this->_emails = $emails;
        $this->_from = $from;
        $this->_replyTo = $replyTo;

        ini_set('sendmail_from',  $replyTo);
    }

    public function put(F_Debugger_Message $message) {

        foreach($this->_emails as $email) {
            $this->_mail($email, $this->_from.": ". $message->getShort(), $message->getHtml());
        }
    }

    private function _mail($email, $subject, $body) {

        $headers = "From: ". $this->_from . " <" . $this->_replyTo . ">\r\n"; //optional headerfields
        mail($email, $subject, $body, $headers);
        
    }
}