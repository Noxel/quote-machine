<?php

namespace App\Event;


use App\Service\MessageGenerator;
use Symfony\Component\EventDispatcher\Event;

class UpdateQuoteEvent extends Event
{
    const NAME = 'update.quote';

    public function getMessage(){
        return 'Update';
    }
}