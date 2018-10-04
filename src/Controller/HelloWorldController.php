<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelloWorldController extends Controller
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
    public function list($nb = 1)
    {
        $html = 'Yo';

        for ($i = 0; $i <$nb; $i++) {
            $html.=' Ho';
        }

        return new Response('<html><body>'.$html.' </body></html>');
    }

    /**
     * @Route("/hello2/{nom}")
     */
    public function hello2($nom = 'lol')
    {
        return $this->render('/hello.html.twig', [
            'nom' => $nom
        ]);
    }

    /**
     * @Route("/Pirates{nbr}/{mot}")
     */
    public function Pirates($nbr =1, $mot = 'kek')
    {
        if ($nbr > 9999) {
            $nbr = 9999;
        }

        return $this->render('/pirate.html.twig', [
            'nbr' => $nbr,
            'mot' => $mot
        ]);
    }
}
