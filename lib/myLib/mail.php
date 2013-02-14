<?php

class Mail {

  private
    $to,
    $from,
    $cc,
    $subject,
    $headers,
    $message;
  /**
    Getters
    @return string
  **/
  function send() {
    return mail($this->to, $this->subject, $this->message, $this->headers);
  }

  function __construct($to, $from, $cc, $subject, $contenu) 
  {
    $this->to=$to;
    $this->from=$from;
    $this->cc=$cc;
    $this->subject=$subject;
    $this->headers='MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n" . "To: $to" . "\r\n" . "From: $from" . "\r\n" . "Cc: $cc" . "\r\n";
    $this->message='<html><head><title>'.$subject.'</title><meta http-equiv="content-type" content="text/html; charset=utf-8" /></head><body>'.$contenu.'</body></html>';
    return $this;
  }
}