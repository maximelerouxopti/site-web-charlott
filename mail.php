<?php

require_once("config.php");


class Mail {
    var $subject;
    var $headers;
    var $body;
    var $from;
    var $to;
    
    function Mail($to, $subject, $content) {
        global $cfg_mail;
        
        $this->from = $cfg_mail["postmaster"];
        $this->to = $to;
        $this->subject = $subject;
        $this->_makeBody($content);
        $this->_makeHeaders();
    }
    
    function _makeHeaders() {
        $this->headers  = "MIME-Version: 1.0 \n";
        $this->headers .= "Content-Transfer-Encoding: 8bit \n";
        $this->headers .= "Content-type: text/html; charset=utf-8 \n";
        $this->headers .= "From: " . $this->from . " \n";
    }
    
    function _makeBody($content) {
        $this->body  = "";
        $this->body .= "<html>";
        $this->body .= "<head><title> Subject </title></head>";
        $this->body .= "<body>" . $content . "</body>";
        $this->body .= "</html>";
    }
    
    function send() {
        return @mail(
            $this->to,
            $this->subject,
            $this->body,
            $this->headers
        );
    }
}

?>