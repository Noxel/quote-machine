<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuoteController extends Controller{

    /**
     * @Route("/quotes")
     */
    public function QuotesAction(){
        $files = file_get_contents('../var/quotes.json');
        $quotes = json_decode($files, true);
        return $this->render('/quotes.html.twig',[
            'quotes' => $quotes
            ]);

    }

    /**
     * @Route("/search")
     */
    public function SearchAction(){
        $quotes='';
        if(isset($_GET['search'])){
            $files = file_get_contents('../var/quotes.json');
            $json = json_decode($files, true);
            foreach ($json as $quote){
                if(strpos($quote['quote'], $_GET['search']) !== false){
                    
                }

            }


        }


        return $this->render('/search.html.twig',[
            'quotes' => $quotes
        ]);

    }


}