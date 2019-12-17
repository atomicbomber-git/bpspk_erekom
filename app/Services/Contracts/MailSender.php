<?php

interface MailSender
{
    public function sendMail(
        string $host,
        string $from,
        string $to,
        string $title,
        string $text = "",
        string $html = ""
    );
}