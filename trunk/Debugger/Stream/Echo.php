<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 1/26/13
 */

class F_Debugger_Stream_Echo  extends F_Debugger_Stream {

    public function put(F_Debugger_Message $message) {

        echo $message->getHtml();
    }

}