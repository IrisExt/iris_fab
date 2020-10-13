<?php

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Swift_Transport;

class EmailConsumer implements ConsumerInterface
{
    /** @var ContainerInterface $container */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, Swift_Transport $swift_Transport)
    {
        $this->container = $container;
        $this->swift_Transport = $swift_Transport;
    }

    /**
     * @param AMQPMessage $msg
     * @return bool
     */
    public function execute(AMQPMessage $msg)
    {
        return $this->processMessage($msg);
    }

    /**
     * @param AMQPMessage $msg
     * @return int
     */
    public function processMessage(AMQPMessage $msg)
    {
        $email = unserialize($msg->getBody(), ['allowed_classes' => true]);
       // echo 'L\'email suivant a été consomé : ', $email->getBody(), PHP_EOL, PHP_EOL;
       // $transport = $this->getContainer()->get('swiftmailer.transport.real');

        $mailer = new \Swift_Mailer($this->swift_Transport);
        $mailer->send($email);
        // To be completed later in the tutorial
       return ConsumerInterface::MSG_ACK;
    }
}
