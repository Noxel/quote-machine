<?php

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

class CreateQuoteEvent extends Event
{
    const NAME = 'create.quote';

    public function getMessage(){
        return 'Create';
    }
}