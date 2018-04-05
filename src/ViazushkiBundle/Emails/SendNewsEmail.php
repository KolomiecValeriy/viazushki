<?php

namespace ViazushkiBundle\Emails;


use Doctrine\ORM\EntityManager;

class SendNewsEmail
{
    /**
     * @var SendEmail
     */
    private $sendEmail;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var \Twig_Environment
     */
    private $templating;

    /**
     * @var string
     */
    private $viazushkiEmail;

    public function __construct(EntityManager $entityManager, \Twig_Environment $templating, $viazushkiEmail, SendEmail $sendEmail)
    {
        $this->sendEmail = $sendEmail;
        $this->em = $entityManager;
        $this->viazushkiEmail = $viazushkiEmail;
        $this->templating = $templating;
    }

    public function sendNews(string $subject)
    {
        $subscribers = $this->em->getRepository('ViazushkiBundle:User')->findSubscribeUsers();

        $date = new \DateTime('-1 day');
        $newToys = $this->em->getRepository('ViazushkiBundle:Toy')->findNewToys($date->format('Y-m-d'));

        if (!$subscribers || !$newToys) {
            return false;
        }

        $iteratebleSubscribers = $subscribers->iterate();
        foreach ($iteratebleSubscribers as $row) {
            $this->sendEmail->send(
                $subject,
                $this->viazushkiEmail,
                $row[0]->getEmail(),
                $this->templating->render('@Viazushki/Email/newToys.html.twig', [
                    'newToys' => $newToys,
                ])
            );
            $this->em->clear();
        }

        return true;
    }
}