<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuoteController extends Controller{



    /**
     * @Route("/quotes")
     */
    public function SearchAction(){
        $quotes=[];
        if(isset($_GET['search'])){
            $files = file_get_contents('../var/quotes.json');
            $json = json_decode($files, true);
            foreach ($json as $quote){
                if(stripos($quote['quote'], $_GET['search']) !== false){
                    $quotes[] = $quote;
                }

            }
        }
        return $this->render('/quotes.html.twig',[
            'quotes' => $quotes
        ]);

    }


}