<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Quote;
use App\Form\CategoryType;
use App\Form\QuoteSearchType;
use App\Form\QuoteType;
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
    public function rechercher(Request $request)
    {
        $quotes = [];
        $quoteRep = $this->getDoctrine()->getRepository(Quote::class);


        if ($request->request->get('quote_search')) {
            $src = $request->request->get('quote_search')['search'];

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
            'quotes' => $this->rechercher($request),
            'form' => $form->createView(),
            'formAdd' => $formAdd->createView()
        ]);
    }

    /**
     * @Route("/Update/{id}", name="update_quote")
     */
    public function update(Quote $quote, Request $request)
    {
        $quoteRep = $this->getDoctrine()->getManager();

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

        return $this->render('/update.html.twig', [
            'formAdd' => $formAdd->createView(),
        ]);
    }
    /**
     * @Route("/Delete/{id}", name="delete_quote")
     */
    public function delete(Quote $quote)
    {
        $quoteRep = $this->getDoctrine()->getManager();
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
    public function random()
    {
        $quoteRep = $this->getDoctrine()->getRepository(Quote::class);
        $quotes = $quoteRep->findAll();
        $quote = $quotes[rand(0, sizeof($quotes) -1)];

        return $this->render('/randomQuote.html.twig', [
            'quote' => $quote,
        ]);
    }

    /**
     * @Route("/Categorie", name="categorie")
     */
    public function categorie(Request $request)
    {
        $catManager = $this->getDoctrine()->getManager();
        $catRep = $this->getDoctrine()->getRepository(Category::class);

        $cat = new Category();
        $formAdd = $this->createForm(CategoryType::class, $cat);

        $formAdd->handleRequest($request);

        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $catManager->persist($cat);
            $catManager->flush();


            $this->addFlash(
                'notice',
                'Your add were saved!'
            );
        }

        return $this->render('/categorie.html.twig', [
            'cats' => $catRep->findAll(),
            'formAdd' => $formAdd->createView()
        ]);
    }

    /**
     * @Route("/UpdateCategorie{id}", name="update_categorie")
     */
    public function updateCategorie(Category $cat, Request $request)
    {
        $quoteRep = $this->getDoctrine()->getManager();
        $formAdd = $this->createForm(CategoryType::class, $cat);
        $formAdd->handleRequest($request);

        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $quoteRep->flush();


            $this->addFlash(
                'notice',
                'Your update were saved!'
            );


            return $this->redirectToRoute('categorie');
        }

        return $this->render('/update.html.twig', [
            'formAdd' => $formAdd->createView(),
        ]);
    }

    /**
     * @Route("/CategorieDelete{id}", name="delete_categorie")
     */
    public function deleteCategorie(Category $cat)
    {
        $quoteRep = $this->getDoctrine()->getManager();
        foreach ($cat->getQuotes() as $quote) {
            $quoteRep->remove($quote);
        }

        $quoteRep->remove($cat);
        $quoteRep->flush();


        $this->addFlash(
            'notice',
            'Your delete were saved!'
        );

        return $this->redirectToRoute('categorie');
    }

    /**
     * @Route("/QuoteByCategorie/{slug}", name="quotebycategorie_categorie")
     */
    public function quoteByCategorie($slug)
    {
        $cats = $this->getDoctrine()->getRepository(Category::class)->findBy(['slug'=>$slug]);
        $cat = array_shift($cats);

        return $this->render('/quoteByCategorie.html.twig', [
            'quotes' => $cat->getQuotes() ,
            'cat' => $cat,
        ]);
    }
}
