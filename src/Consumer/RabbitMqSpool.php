<?php

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\Consumer;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Swift_ConfigurableSpool;
use Swift_Mime_Message;
use Swift_Transport;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Swift_Mime_SimpleMessage;

class RabbitMqSpool extends Swift_ConfigurableSpool
{
    /** @var ContainerInterface $container */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Starts this Spool mechanism.
     */
    public function start()
    {
    }

    /**
     * Stops this Spool mechanism.
     */
    public function stop()
    {
    }

    /**
     * Tests if this Spool mechanism has started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Queues a message.
     *
     * @param Swift_Mime_Message $message The message to store
     *
     * @return bool Whether the operation has succeeded
     */
    public function queueMessage(Swift_Mime_SimpleMessage $message)
    {
        $serialized = serialize($message);
        $this->getMailProducer()->publish($serialized);
    }

    /**
     * Sends messages using the given transport instance.
     *
     * @param Swift_Transport $transport A transport instance
     * @param string[] $failedRecipients An array of failures by-reference
     *
     * @return int The number of sent emails
     */
    public function flushQueue(Swift_Transport $transport, &$failedRecipients = null)
    {
        return $this->getConsumer()->consume($this->getMessageLimit());
    }

    protected function getConsumer() {
        return $this->container->get('old_sound_rabbit_mq.mails_queue_consumer');
    }


    protected function getMailProducer() {
        return $this->container->get('old_sound_rabbit_mq.mails_queue_producer');
    }
}