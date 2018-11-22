<?php

namespace App\Event;


use App\Service\MessageGenerator;
use Symfony\Component\EventDispatcher\Event;

class DeleteQuoteEvent extends Event
{
    const NAME = 'delete.quote';

    protected $msg;

    public function __construct(MessageGenerator $msg)
    {
        $this->msg = $msg->getMessageDelete();
    }

    public function getMessage(){
        return $this->msg;
    }
}