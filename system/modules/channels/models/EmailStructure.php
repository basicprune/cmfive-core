<?php

class EmailStructure {

    public $to;
    public $cc;
    public $from;
    public $from_email_address;
    public $subject;
    public $body = array("plain" => "", "html" => "");

    // Maybe
    public $attachments = array();

}