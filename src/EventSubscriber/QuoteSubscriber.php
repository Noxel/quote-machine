<?php


namespace App\EventSubscriber;


use App\Event\DeleteQuoteEvent;
use App\Event\UpdateQuoteEvent;
use App\Event\CreateQuoteEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Service\MessageGenerator;

class QuoteSubscriber implements EventSubscriberInterface
{
    private $logger;
    private $msg;

    public function __construct(LoggerInterface $logger, MessageGenerator $msg)
    {
        $this->logger = $logger;
        $this->msg = $msg;

    }

    public static function getSubscribedEvents()
    {
        return array(
            'delete.quote'  => 'onDeleteQuote',
            'create.quote' => 'onCreateQuote',
            'update.quote' => 'onUpdateQuote'
        );
    }

    public  function onDeleteQuote(DeleteQuoteEvent $event){
        $this->logger->info($this->msg->getMessageDelete());

    }
    public  function onUpdateQuote(UpdateQuoteEvent $event){
        $this->logger->critical($this->msg->getMessageUpdate());


    }
    public  function onCreateQuote(CreateQuoteEvent $event){
        $this->logger->debug($this->msg->getMessage());

    }
}