<?php

namespace SoftUniBundle\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use SoftUniBundle\Entity\Product;
use SoftUniBundle\Entity\ProductCategory;

/**
 * Class NotifyManager
 * @package SoftUniBundle\Service
 * @author Plamen Markov <plamen@lynxlake.org>
 */
class NotifyManager
{
    private $container;

    /**
     * NotifyManager constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $emails
     * @param string $subject
     * @param string $template
     * @return int The number of successful recipients. Can be 0 which indicates failure
     */
    public function sendEmails(array $emails, $subject, $template)
    {
        if (empty($emails)) {
            return 0;
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(['plamen326@gmail.com' => 'Plamen Markov'])
            ->addTo($emails[0])
            ->setBody($template, 'text/html');
        if (1 < count($emails)) {
            for ($i = 1; $i < count($emails); $i++) {
                $message->addCc($emails[$i]);
            }
        }

        return $this->container->get('mailer')->send($message);
    }
}
