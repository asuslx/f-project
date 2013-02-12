<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 1/26/13
 */

class F_Debugger_Message {

    private $_errno;
    private $_errmsg;
    private $_filename;
    private $_linenum;
    private $_vars;

    private static $_typeDescr = array(

    );

    public function __construct($errno, $errmsg, $filename, $linenum, $vars) {

        $this->_errno = $errno;
        $this->_errmsg = $errmsg;
        $this->_filename = $filename;
        $this->_linenum = $linenum;
        $this->_vars = $vars;
    }

    public function getShort() {
        return $this->_formatErrorHtml();
    }

    public function getHtml() {

        return "<div style=\"position: relative; font-size: 14px; color: #000000; background-color: #EEEEEE;z-index: 1000;\"><br/>".$this->_formatErrorHtml() . "<br/><br/><hr/><br/>" . $this->_formatBacktraceHtml(). "<hr/></div></br/>";
    }


    public function getText() {
        return "\n\n{$this->_formatErrorText()}\n{$this->_formatBacktraceText()}\n\n";
    }
    
    private static function _type($type)  {

        switch($type)
        {
            case E_ERROR: // 1 //
                return 'E_ERROR';
            case E_WARNING: // 2 //
                return 'E_WARNING';
            case E_PARSE: // 4 //
                return 'E_PARSE';
            case E_NOTICE: // 8 //
                return 'E_NOTICE';
            case E_CORE_ERROR: // 16 //
                return 'E_CORE_ERROR';
            case E_CORE_WARNING: // 32 //
                return 'E_CORE_WARNING';
            case E_CORE_ERROR: // 64 //
                return 'E_COMPILE_ERROR';
            case E_CORE_WARNING: // 128 //
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR: // 256 //
                return 'E_USER_ERROR';
            case E_USER_WARNING: // 512 //
                return 'E_USER_WARNING';
            case E_USER_NOTICE: // 1024 //
                return 'E_USER_NOTICE';
            case E_STRICT: // 2048 //
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR: // 4096 //
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED: // 8192 //
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED: // 16384 //
                return 'E_USER_DEPRECATED';
        }
        return "";
    }

    private function _formatErrorHtml() {
        $out = "<b>" .self::_type($this->_errno). ":</b>&nbsp;<font color=\"#DD3333\"> <b>".$this->_errmsg . "</b></font>&nbsp; File: <b>{$this->_filename}</b> <font color=\"#DD3333\"><b>({$this->_linenum})</b></font>";
        return $out;
    }

    private function _formatBacktraceHtml() {
        $out= "<b>Backtrace:</b> <br/></br/>";
        $backtrace = debug_backtrace();
        foreach($backtrace as $bt) {

            if(isset($bt['class']) && strstr($bt['class'], 'F_Debugger')) {
                continue;
            }
            if(isset($bt['class'])) {
                $out .= "&nbsp;&nbsp;&nbsp;<font color=\"#DD3333\"><b> {$bt['class']}</b></font>";
            }
            if(isset($bt['function'])) {
                if(!isset($bt['class'])) { $out .= "&nbsp;&nbsp;&nbsp;&nbsp;";}
                else {
                    if(isset($bt['type'])) $out .= $bt['type'];
                }
                $out .= "<font color=\"#DD3333\"><b>{$bt['function']} </b></font>";
            }
            if(isset($bt['file'])) {
                $out .= "&nbsp;File: <b>{$bt['file']}</b>";
            }
            if(isset($bt['line'])) {
                $out .= "<font color=\"#DD3333\"><b> ({$bt['line']})</b></font> ";
            }

            $out .= "<br/>";
        }
        return $out. "</br/>";
    }

        private function _formatErrorText() {
        $out = self::_type($this->_errno). ": ".$this->_errmsg . " File: {$this->_filename} ({$this->_linenum})";
        return $out;
    }

    private function _formatBacktraceText() {
        $out= "Backtrace: \n";
        $backtrace = debug_backtrace();
        foreach($backtrace as $bt) {

            if(isset($bt['class']) && strstr($bt['class'], 'F_Debugger')) {
                continue;
            }
            if(isset($bt['class'])) {
                $out .= "   {$bt['class']}::";
            }
            if(isset($bt['function'])) {
                $out .= "{$bt['function']} ";
            }
            if(isset($bt['file'])) {
                $out .= "    {$bt['file']}";
            }
            if(isset($bt['line'])) {
                $out .= " ({$bt['line']}) ";
            }

            $out .= "\n";
        }
        return $out. "\n";
    }

}