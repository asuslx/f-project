<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 1/26/13
 */

class F_Debugger_Stream_Log  extends F_Debugger_Stream {

    private $_file;

    public function __construct($fileName) {

        $this ->_file = fopen($fileName, "a+");
        if(!$this->_file) throw new F_App_Exception("Unable to open log file!");
    }

    public function put(F_Debugger_Message $message) {

        $text = $message->getText();
        fputs($this->_file, $text);
    }

    public function __destruct() {
        fclose($this->_file);
    }
}