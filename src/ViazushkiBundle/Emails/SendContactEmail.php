<?php

namespace ViazushkiBundle\Emails;


use Twig_Environment;

class SendContactEmail
{
    /**
     * @var SendEmail
     */
    private $sendEmail;

    /**
     * @var Twig_Environment
     */
    private $templating;

    /**
     * @var string
     */
    private $viazushkiEmail;

    public function __construct(\Twig_Environment $templating, SendEmail $sendEmail, $viazushkiEmail)
    {
        $this->templating = $templating;
        $this->sendEmail = $sendEmail;
        $this->viazushkiEmail = $viazushkiEmail;
    }

    /**
     * Sending message
     * @param string $subject
     * @param string $name
     * @param string $email
     * @param string $text
     *
     * @return bool
     */
    public function send(string $subject, string $name, string $email, string $text)
    {
        return $this->sendEmail->send(
            $subject,
            $this->viazushkiEmail,
            $this->viazushkiEmail,
            $this->templating->render('@Viazushki/Email/contactEmail.html.twig', [
                'name' => $name,
                'email' => $email,
                'message' => $text,
            ])
        );
    }
}
