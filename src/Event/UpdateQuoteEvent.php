<?php

namespace App\Event;


use App\Service\MessageGenerator;
use Symfony\Component\EventDispatcher\Event;

class UpdateQuoteEvent extends Event
{
    const NAME = 'update.quote';

    protected $msg;

    public function __construct(MessageGenerator $msg)
    {
        $this->msg = $msg->getMessageUpdate();
    }

    public function getMessage(){
        return $this->msg;
    }
}