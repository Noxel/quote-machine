<?php


namespace App\EventSubscriber;


use App\Event\DeleteQuoteEvent;
use App\Event\UpdateQuoteEvent;
use App\Event\CreateQuoteEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QuoteSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

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
        $this->logger->info($event->getMessage());

    }
    public  function onUpdateQuote(UpdateQuoteEvent $event){
        $this->logger->critical($event->getMessage());


    }
    public  function onCreateQuote(CreateQuoteEvent $event){
        $this->logger->debug($event->getMessage());

    }
}