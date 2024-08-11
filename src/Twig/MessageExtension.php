<?php

namespace App\Twig;


use Twig\TwigFunction;

use App\Entity\Message;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;


class MessageExtension extends AbstractExtension
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFunctions(): array
    {
        //on cree l'extension twig, 'moderation est le nom du variable, getTotalModeration est la fonction appeler'
        return [new TwigFunction('nbrMessage', [$this, 'getTotalMessagesUser'])];
    }

    public function getTotalMessagesUser(): int
    {
        $messages = $this->entityManager->getRepository(Message::class)->findBy(['isRead' => false]);


        return count($messages);
    }
}
