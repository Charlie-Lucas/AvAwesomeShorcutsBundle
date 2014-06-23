<?php

namespace AppVentus\Awesome\ShortcutsBundle\Service;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * This service provides functions of the shortcut bundle.
 *
 * @author Thomas Beaujean <thomas@appventus.com>
 */
class ShortcutService
{
    protected $mailerSpool = null;
    protected $mailer = null;

    /**
     * Constructor
     * @param unknown $mailerSpool
     * @param unknown $mailer
     */
    public function __construct($mailerSpool, $mailer)
    {
        $this->mailerSpool = $mailerSpool;
        $this->mailer = $mailer;
    }

    /**
     * Create a swift mail and queue it
     *
     * @param string $subject
     * @param string $from
     * @param string $to
     * @param string $body
     * @param string $contentType
     * @param string $replyTo
     */
    public function createAndQueueMail($subject, $from, $to, $body, $contentType = null, $replyTo = null)
    {
        $mailerSpool = $this->mailerSpool;

        //the mailer spool might not have been installed
        if ($mailerSpool === null) {
            throw \Exception('The mailer spool service is null, please check that the first argument of av.shorcuts exists. You might have not installed white_october.swiftmailer.');
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $contentType);

        //add the reply to
        if ($replyTo != null) {
            $message->setReplyTo($replyTo);
        }

        //queue the message
        $mailerSpool->queueMessage($message);
    }

    /**
     * Create and mail and send it now
     *
     * @param string $subject
     * @param string $from
     * @param string $to
     * @param string $body
     * @param string $contentType
     * @param string $replyTo
     * @param string $mailer
     */
    public function createAndSendMail($subject, $from, $to, $body, $contentType = null, $replyTo = null)
    {
        $mailer = $this->mailer;

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $contentType);

        //add the reply to
        if ($replyTo != null) {
            $message->setReplyTo($replyTo);
        }

        //send the message
        $mailer->send($message);
    }


    public function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

}

