<?php

namespace App\Service;


class MessageGenerator
{
    public function getMessage(){
        $messages = [
            'Bien jouer !!',
            'beau travail ! Continue !',
            'Dududupuis !'
        ];

        $index = array_rand($messages);
        return $messages[$index];
    }

    public function getMessageUpdate(){
        $messages = [
            'La meilleur update de ma vie :0',
            'J\' aurai pas dit mieux',
            'Camion :D '
        ];

        $index = array_rand($messages);
        return $messages[$index];
    }

    public function getMessageDelete(){
        $messages = [
            'Adieux quote :\'( )',
            'Bye bey ',
            'C\'est vrai que cela géner'
        ];

        $index = array_rand($messages);
        return $messages[$index];
    }
}