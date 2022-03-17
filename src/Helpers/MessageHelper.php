<?php

namespace App\Helpers;

use Symfony\Component\Mailer\MailerInterface;
use App\Entity\User;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;


class MessageHelper
{
    private MailerInterface $mailer;

    /**
     * Class constructor.
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function sendMessage(User $user)
    {
        $message = (new Email())
            ->from(new Address('clementine.digny@gmail.com', 'This School Bot'))
            ->to($user->getEmail())
            ->subject('Please Confirm your Email')
            ->html('registration/confirmation_email.html.twig');
        $this->mailer->send($message);
    }
}
