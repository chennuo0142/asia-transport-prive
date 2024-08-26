<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;


class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailerInterface)
    {
        $this->mailer = $mailerInterface;
    }
    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ) {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("email/$template.html.twig")
            ->context($context);

        $this->mailer->send($email);
    }
}
