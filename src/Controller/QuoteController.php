<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Quote;
use App\Entity\Score;
use App\Event\CreateQuoteEvent;
use App\Event\UpdateQuoteEvent;
use App\Event\DeleteQuoteEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\EventSubscriber\QuoteSubscriber;
use App\Form\CategoryType;
use App\Form\QuoteSearchType;
use App\Form\QuoteType;
use App\Service\MessageGenerator;
use App\Util\Slugger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
    public function quote(Request $request, MessageGenerator $msg,  EventDispatcherInterface $dispatcher)
    {
        $form = $this->createForm(QuoteSearchType::class);

        $quote = new Quote();
        $quoteRep = $this->getDoctrine()->getManager();

        $formAdd = $this->createForm(QuoteType::class, $quote);

        $formAdd->handleRequest($request);


        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_USER');

            $quote->setOwner( $this->getUser());
            $quoteRep->persist($quote);
            $quoteRep->flush();

            $event = new CreateQuoteEvent($msg);
            $dispatcher->dispatch(CreateQuoteEvent::NAME, $event);

            $this->addFlash(
                'notice',$msg->getMessage()
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
     * @IsGranted("ROLE_USER")
     */
    public function update(Quote $quote, Request $request, MessageGenerator $msg, EventDispatcherInterface $dispatcher)
    {
        $this->denyAccessUnlessGranted('edit', $quote);
        $quoteRep = $this->getDoctrine()->getManager();

        $formAdd = $this->createForm(QuoteType::class, $quote);
        $formAdd->handleRequest($request);

        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $quoteRep->flush();

            $event = new UpdateQuoteEvent($msg);
            $dispatcher->dispatch(UpdateQuoteEvent::NAME, $event);

            $this->addFlash(
                'notice',
                $msg->getMessageUpdate()
            );


            return $this->redirectToRoute('quotes');
        }

        return $this->render('/update.html.twig', [
            'formAdd' => $formAdd->createView(),
        ]);
    }
    /**
     * @Route("/Delete/{id}", name="delete_quote")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Quote $quote, MessageGenerator $msg,  EventDispatcherInterface $dispatcher)
    {
        $this->denyAccessUnlessGranted('delete', $quote);

        $quoteRep = $this->getDoctrine()->getManager();
        $quoteRep->remove($quote);
        $quoteRep->flush();

        $event = new DeleteQuoteEvent($msg);
        $dispatcher->dispatch(DeleteQuoteEvent::NAME, $event);

        $this->addFlash(
            'notice',$msg->getMessageDelete()
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
    public function categorie(Request $request, MessageGenerator $msg)
    {
        $catManager = $this->getDoctrine()->getManager();
        $catRep = $this->getDoctrine()->getRepository(Category::class);

        $cat = new Category();
        $formAdd = $this->createForm(CategoryType::class, $cat);

        $formAdd->handleRequest($request);

        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $cat->setSlug(Slugger::slugify($cat->getName()));
            $catManager->persist($cat);
            $catManager->flush();


            $this->addFlash(
                'notice',$msg->getMessage()
            );
        }

        return $this->render('/categorie.html.twig', [
            'cats' => $catRep->findAll(),
            'formAdd' => $formAdd->createView()
        ]);
    }

    /**
     * @Route("/UpdateCategorie/{id}", name="update_categorie")
     * @IsGranted("ROLE_ADMIN")
     */
    public function updateCategorie(Category $cat, Request $request, MessageGenerator $msg)
    {
        $quoteRep = $this->getDoctrine()->getManager();
        $formAdd = $this->createForm(CategoryType::class, $cat);
        $formAdd->handleRequest($request);

        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $quoteRep->flush();


            $this->addFlash(
                'notice',$msg->getMessage()
            );


            return $this->redirectToRoute('categorie');
        }

        return $this->render('/update.html.twig', [
            'formAdd' => $formAdd->createView(),
        ]);
    }

    /**
     * @Route("/CategorieDelete/{id}", name="delete_categorie")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteCategorie(Category $cat, MessageGenerator $msg)
    {
        $quoteRep = $this->getDoctrine()->getManager();
        foreach ($cat->getQuotes() as $quote) {
            $quoteRep->remove($quote);
        }

        $quoteRep->remove($cat);
        $quoteRep->flush();


        $this->addFlash(
            'notice',$msg->getMessage()
        );

        return $this->redirectToRoute('categorie');
    }

    /**
     * @Route("/QuoteByCategorie/{slug}", name="quotebycategorie_categorie")
     */
    public function quoteByCategorie($slug)
    {
        $cat = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['slug'=>$slug]);


        return $this->render('/quoteByCategorie.html.twig', [
            'quotes' => $cat->getQuotes() ,
            'cat' => $cat,
        ]);
    }

    /**
     * @Route("/QuoteUp/{id}", name="quote_up" )
     * @IsGranted("ROLE_USER")
     */
    public function upScoreQuote(Quote $quote){
        $score = $this->getDoctrine()->getRepository(Score::class)->findOneBy(['Quote' => $quote, 'user' => $this->getUser()]);
        if($score == null){
            $score = new Score();
            $score->setQuote($quote);
            $score->setUser($this->getUser());
            $this->getDoctrine()->getManager()->persist($score);
        }
        $score->up();
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('quotes');
    }

    /**
     * @Route("/QuoteDown/{id}", name="quote_down" )
     * @IsGranted("ROLE_USER")
     */
    public function downScoreQuote(Quote $quote){
        $score = $this->getDoctrine()->getRepository(Score::class)->findOneBy(['Quote' => $quote, 'user' => $this->getUser()]);
        if($score == null){
            $score = new Score();
            $score->setQuote($quote);
            $score->setUser($this->getUser());
            $this->getDoctrine()->getManager()->persist($score);
        }
        $score->down();
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('quotes');
    }
}
