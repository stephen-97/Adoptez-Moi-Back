<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService {

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($email, $token)
    {
        $email = (new TemplatedEmail())
        ->from("PrenezMoiProjet2022@gmail.com")
        ->to(new Address($email))
        ->subject("Prenez moi : Validation de compte")
        ->htmlTemplate('emails/activation.html.twig')
        ->context(['token' => $token,]);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            echo $e;
        }
    }
}

/**
 *
public function adminDeleteAnimal($email, $message)
{
$email = (new TemplatedEmail())
->from("projet2itechstephencamille@gmail.com")
->to(new Address($email))
->subject("Annonce supprimé 'Trouvez moi' ")
->htmlTemplate('emails/animalDeleted.html.twig')
->context(['message' => $message,]);
try {
$this->mailer->send($email);
} catch (TransportExceptionInterface $e) {
echo $e;
}
}
 */