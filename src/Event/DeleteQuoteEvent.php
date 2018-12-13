<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

class DeleteQuoteEvent extends Event
{
    const NAME = 'delete.quote';


    public function getMessage(){
        return 'Delete';
    }
}