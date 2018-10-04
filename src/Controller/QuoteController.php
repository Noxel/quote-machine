<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Form\QuoteSearchType;
use App\Form\QuoteType;
use App\Repository\QuoteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Tests\RequestContentProxy;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuoteController extends Controller
{



    /**
     * @Route("/quotes", name="quotes")
     */
    public function search(Request $request)
    {
        $form = $this->createForm(QuoteSearchType::class);

        $quote = new Quote();
        $quoteRep = new QuoteRepository('../var/quotes.json');

        $formAdd = $this->createForm(QuoteType::class, $quote);

        $formAdd->handleRequest($request);


        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $quote = $formAdd->getData();
            $quoteRep->persist($quote);

            $this->addFlash(
                'notice',
                'Your add were saved!'
            );
        }



        return $this->render('/quotes.html.twig', [
            'quotes' => Quote::search(),
            'form' => $form->createView(),
            'formAdd' => $formAdd->createView()
        ]);
    }

    /**
     * @Route("/Update/{id}", name="update_quote")
     */
    public function update($id = null, Request $request)
    {
        $quoteRep = new QuoteRepository('../var/quotes.json');
        $quote = $quoteRep->find($id);

        $formAdd = $this->createForm(QuoteType::class, $quote);

        $formAdd->handleRequest($request);


        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $quote = $formAdd->getData();
            $quoteRep->persist($quote);


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
        $quoteRep = new QuoteRepository('../var/quotes.json');
        $quoteRep->delete($quoteRep->find($id));

        $this->addFlash(
            'notice',
            'Your delete were saved!'
        );

        return $this->redirectToRoute('quotes');
    }
}
