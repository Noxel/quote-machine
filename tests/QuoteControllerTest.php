<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class QuoteControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp(){
        $this->client = static ::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
    }

    public function testIndex(){
        $this->client->request('GET', '/quotes');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreationQuote(){
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/quotes');

        //Creation quote
        $form = $crawler->selectButton('Submit')->form();
        $form['quote[quote]'] = 'testQ';
        $form['quote[meta]'] = 'testM';
        $form['quote[category]'] = 1;
        $crawler = $this->client->submit($form);

        //verification de l'existence de la quote
        $this->assertGreaterThan(0, $crawler->filter('li:contains("testQ")')->count());
        //verification qu'il y a des lien de Catégory
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Category de quotes")')->count());
    }

    public function testDeleteQuote(){
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/quotes');

        //Delete Quote
        $num = $crawler->filter('a:contains("Category de quotes")')->count();
        $link = $crawler->filter('a[href^="/Delete"]')->eq(1)->link();
        $crawler = $this->client->click($link);
        //verification que on as une quote de moins
        $this->assertLessThan($num, $crawler->filter('a:contains("Category de quotes")')->count());

    }

    public function testUpdateQuote(){
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/quotes');

        //Update Quote
        $link = $crawler->filter('a[href^="/Update"]')->eq(1)->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Submit')->form();
        $form['quote[quote]'] = 'testQUpdate';
        $form['quote[meta]'] = 'testMUpdate';
        $form['quote[category]'] = 1;
        $crawler = $this->client->submit($form);
        //verification de la modification
        $this->assertGreaterThan(0, $crawler->filter('li:contains("testQUpdate")')->count());
    }

    public function testQuoteCategory(){
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/quotes');

        //Quote d'une category
        $num = $crawler->filter('li')->count();
        $link = $crawler->filter('a[href^="/QuoteByCategorie"]')->eq(1)->link();
        $crawler = $this->client->click($link);
        //verification du bon nombre de quote dans la category
        $this->assertEquals($num, $crawler->filter('li')->count());

    }

    public function testSearchQuote(){
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/quotes');

        //Search Quote
        $num = $crawler->filter('li:contains("testQ")')->count();
        $form = $crawler->selectButton('Load')->form();
        $form['quote_search[search]'] = 'testQ';
        $crawler = $this->client->submit($form);
        //Verification que l'on obtien bien le bon nombre de quote avec la recherche
        $this->assertEquals($num, $crawler->filter('li')->count());
    }

    public function testQuoteRandom(){
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/quotes');

        //Quote Random
        $link = $crawler->filter('a[href="/Random"]')->first()->link();
        $crawler = $this->client->click($link);
        $this->assertEquals(1, $crawler->filter('div i')->count());
    }

    public function testListeCategorie(){
        $crawler = $this->client->request('GET', '/Categorie');

        //Liste Categorie
        $this->assertEquals(1, $crawler->filter('li')->count());
    }

    public function testCreationCategorie(){
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/Categorie');

        //Creation Categorie
        $form = $crawler->selectButton('Submit')->form();
        $form['category[name]'] = 'testC';
        $crawler = $this->client->submit($form);
        $this->assertEquals(1, $crawler->filter('li:contains("testC")')->count());
    }

    public function testDeleteCategorie(){
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/Categorie');

        //Delete Categorie
        $link = $crawler->filter('a[href^="/CategorieDelete"]')->first()->link();
        $crawler = $this->client->click($link);
        $this->assertEquals(0, $crawler->filter('li')->count());

    }

    public function testUpdateCategorie(){
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/Categorie');

        //Update Categorie
        $link = $crawler->filter('a[href^="/UpdateCategorie"]')->first()->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Submit')->form();
        $form['category[name]'] = 'testCUpdate';
        $crawler = $this->client->submit($form);
        $this->assertEquals(1, $crawler->filter('li:contains("testCUpdate")')->count());
    }







}