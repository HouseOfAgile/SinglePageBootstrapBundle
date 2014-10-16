<?php

/*
 * This file is part of the SinglePageBundle.
 *
 * (c) Jean-Christophe Meillaud <jc@houseofagile.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HOA\Bundle\SinglePageBundle\EventListener;


use HOA\Bundle\NotificationBundle\Event\NotificationEvent;
use HOA\Bundle\NotificationBundle\Event\NotificationEventInterface;
use HOA\Bundle\NotificationBundle\EventListener\NotificationListener;
use HOA\Bundle\SinglePageBundle\HOASinglePageEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bridge\Monolog\Logger;

class HOAContactListener extends NotificationListener {

    private static $emailTemplates = array(
        HOASinglePageEvents::BETA_REGISTER_COMPLETED=> "HOANotificationBundle:Mails/Register:betaRegister.html.twig",
        HOASinglePageEvents::CONTACT_FORM_FILLED=> "HOANotificationBundle:Mails/Contact:contactSenderEmail.html.twig",
    );
    private static $smsTemplates = array(
        HOASinglePageEvents::CONTACT_FORM_FILLED => "HOANotificationBundle:Sms/Contact:sendWelcomeSms.html.twig",
    );


    public static function getSubscribedEvents()
    {
        return array(
            HOASinglePageEvents::CONTACT_FORM_FILLED => array(
                array('sendEMail', 0),
                array('sendSMS', 1)
                // add more notifications here for this event if needed
            ),
            HOASinglePageEvents::BETA_REGISTER_COMPLETED => array(
                array('sendEMail', 0)
                // add more notifications here for this event if needed
            ),
        );
    }

    public function sendEmail(NotificationEventInterface $event){

        if (!isset(self::$emailTemplates[$event->getName()])) {
            throw new \InvalidArgumentException('This event does not correspond to a known email template');
        }

        $context = array(
            'event' => $event->getEventContext()
        );

        $this->logger->info('Send a Member mail of type ['.$event->getName().']');
        $this->mailerService->sendMessage(
            self::$emailTemplates[$event->getName()],
            $context,
            $event->getMail()
        );
    }

    public function sendSMS(NotificationEventInterface $event) {

    }

    public function sendPushNotification(NotificationEventInterface $event)
    {

    }
}