<?php

namespace App\Event;


use App\Service\MessageGenerator;
use Symfony\Component\EventDispatcher\Event;

class CreateQuoteEvent extends Event
{
    const NAME = 'create.quote';

    protected $msg;

    public function __construct(MessageGenerator $msg)
    {
        $this->msg = $msg->getMessage();
    }

    public function getMessage(){
        return $this->msg;
    }
}