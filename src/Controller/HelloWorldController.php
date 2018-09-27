<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController
{
     /**
      * @Route("/hello/{page}" , name="helloWorld" )
      */
    public function index($page =1)
    {
        return new Response('<html><body>Hello '.$page.'</body></html>');
    }

    /**
     * @Route("/Pirate{nb}", name="Pirate")
     */
    public function list($nb = 1){
        $html = 'Yo';
        for($i = 0; $i <$nb; $i++ ){
            $html.=' Ho';
        }

        return new Response('<html><body>'.$html.' </body></html>');
    }
}
