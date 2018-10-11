<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Form\QuoteSearchType;
use App\Form\QuoteType;
use App\Repository\QuoteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuoteController extends Controller
{

    /**
     * Methode de recherche en fonction du POST[quote][search]
     *
     * @return array de Quote
     */
    public function rechercher()
    {
        $quotes = [];
        $quoteRep = $this->getDoctrine()->getRepository(Quote::class);
        if (isset($_POST['quote_search']['search'])) {
            $src = $_POST['quote_search']['search'];

            if (isset($src)) {

                $quotes = $quoteRep->findQuotes($src);

            }
        } else {
            $quotes = $quoteRep->findAll();
        }

        return $quotes;
    }



    /**
     * @Route("/quotes", name="quotes")
     */
    public function quote(Request $request)
    {
        $form = $this->createForm(QuoteSearchType::class);

        $quote = new Quote();
        $quoteRep = $this->getDoctrine()->getManager();

        $formAdd = $this->createForm(QuoteType::class, $quote);

        $formAdd->handleRequest($request);


        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $quoteRep->persist($quote);
            $quoteRep->flush();


            $this->addFlash(
                'notice',
                'Your add were saved!'
            );
        }



        return $this->render('/quotes.html.twig', [
            'quotes' => $this->rechercher(),
            'form' => $form->createView(),
            'formAdd' => $formAdd->createView()
        ]);
    }

    /**
     * @Route("/Update/{id}", name="update_quote")
     */
    public function update($id = null, Request $request)
    {
        $quoteRep = $this->getDoctrine()->getManager();
        $quote = $quoteRep->getRepository(Quote::class)->find($id);

        $formAdd = $this->createForm(QuoteType::class, $quote);

        $formAdd->handleRequest($request);


        if ($formAdd->isSubmitted() && $formAdd->isValid()) {


            $quoteRep->flush();


            $this->addFlash(
                'notice',
                'Your update were saved!'
            );


            return $this->redirectToRoute('quotes');
        }

        return $this->render('/updateQuotes.html.twig', [
            'formAdd' => $formAdd->createView(),
        ]);
    }
    /**
     * @Route("/Delete/{id}", name="delete_quote")
     */
    public function delete($id = null)
    {
        $quoteRep = $this->getDoctrine()->getManager();
        $quote = $quoteRep->getRepository(Quote::class)->find($id);
        $quoteRep->remove($quote);
        $quoteRep->flush();

        $this->addFlash(
            'notice',
            'Your delete were saved!'
        );

        return $this->redirectToRoute('quotes');
    }

    /**
     * @Route("/Random", name="random_quote")
     */
    public function random($id = null)
    {

        $quoteRep = $this->getDoctrine()->getRepository(Quote::class);
        $quotes = $quoteRep->findAll();
        $quote = $quotes[rand(0, sizeof($quotes) -1)];

        return $this->render('/randomQuote.html.twig', [
            'quote' => $quote,
        ]);
    }



}
