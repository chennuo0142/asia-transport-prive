<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;


class MailerService
{
    private $mailer;
    private $from = "booking@paris-prestige-transfert.fr";

    public function __construct(MailerInterface $mailerInterface)
    {
        $this->mailer = $mailerInterface;
    }
    public function send(
        string $to,
        string $subject,
        string $template,
        array $context
    ) {
        $email = (new TemplatedEmail())
            ->from($this->from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("email/$template.html.twig")
            ->context($context);

        $this->mailer->send($email);
    }
}
