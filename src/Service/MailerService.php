<?php
// src/Service/MailerService.php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;
    private $replyTo;

    public function __construct(MailerInterface $mailer, string $replyTo)
    {
        $this->mailer = $mailer;
        $this->replyTo = $replyTo;
    }

    public function sendEmail(
        string $to,
        string $content = '<p>See Twig integration for better HTML integration!</p>',
        string $subject = 'Time for Symfony Mailer!'
    ): void {
        $email = (new Email())
            ->from('rayenfarhani9@gmail.com')
            ->to($to)
            ->replyTo($this->replyTo)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }
}
