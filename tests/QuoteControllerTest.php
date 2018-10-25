<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuoteControllerTest extends WebTestCase
{
    public function testCrudQuote(){

        $client = static ::createClient();
        $client->followRedirects();

        //Index
        $crawler = $client->request('GET', '/quotes');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Creation quote
        $form = $crawler->selectButton('Submit')->form();
        $form['quote[quote]'] = 'testQ';
        $form['quote[meta]'] = 'testM';
        $form['quote[category]'] = 1;
        $crawler = $client->submit($form);

        //verification de l'existence de la quote
        $this->assertGreaterThan(0, $crawler->filter('li:contains("testQ")')->count());
        //verification qu'il y a des lien de Catégory
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Category de quotes")')->count());

        //Delete Quote
        $num = $crawler->filter('a:contains("Category de quotes")')->count();
        $link = $crawler->filter('a[href^="/Delete"]')->eq(1)->link();
        $crawler = $client->click($link);
        //verification que on as une quote de moins
        $this->assertLessThan($num, $crawler->filter('a:contains("Category de quotes")')->count());

        //Update Quote
        $link = $crawler->filter('a[href^="/Update"]')->eq(1)->link();
        $crawler = $client->click($link);
        $form = $crawler->selectButton('Submit')->form();
        $form['quote[quote]'] = 'testQUpdate';
        $form['quote[meta]'] = 'testMUpdate';
        $form['quote[category]'] = 1;
        $crawler = $client->submit($form);
        //verification de la modification
        $this->assertGreaterThan(0, $crawler->filter('li:contains("testQUpdate")')->count());


        //Quote d'une category
        $num = $crawler->filter('li')->count();
        $link = $crawler->filter('a[href^="/QuoteByCategorie"]')->eq(1)->link();
        $crawler = $client->click($link);
        //verification du bon nombre de quote dans la category
        $this->assertEquals($num, $crawler->filter('li')->count());

        //Passage a quotes
        $link = $crawler->filter('a[href="/quotes"]')->first()->link();
        $crawler = $client->click($link);

        //Search Quote
        $num = $crawler->filter('li:contains("testQ")')->count();
        $form = $crawler->selectButton('Load')->form();
        $form['quote_search[search]'] = 'testQ';
        $crawler = $client->submit($form);
        //Verification que l'on obtien bien le bon nombre de quote avec la recherche
        $this->assertEquals($num, $crawler->filter('li')->count());


        //Quote Random
        $link = $crawler->filter('a[href="/Random"]')->first()->link();
        $crawler = $client->click($link);
        $this->assertEquals(1, $crawler->filter('div i')->count());

        //Back a quotes
        $link = $crawler->filter('a[href="/quotes"]')->first()->link();
        $crawler = $client->click($link);

        //Liste Categorie
        $link = $crawler->filter('a[href="/Categorie"]')->first()->link();
        $crawler = $client->click($link);
        $this->assertEquals(1, $crawler->filter('li')->count());

        //Creation Categorie
        $form = $crawler->selectButton('Submit')->form();
        $form['category[name]'] = 'testC';
        $crawler = $client->submit($form);
        $this->assertEquals(1, $crawler->filter('li:contains("testC")')->count());

        //Delete Categorie
        $link = $crawler->filter('a[href^="/CategorieDelete"]')->first()->link();
        $crawler = $client->click($link);
        $this->assertEquals(1, $crawler->filter('li')->count());

        //Update Categorie
        $link = $crawler->filter('a[href^="/UpdateCategorie"]')->first()->link();
        $crawler = $client->click($link);
        $form = $crawler->selectButton('Submit')->form();
        $form['category[name]'] = 'testCUpdate';
        $crawler = $client->submit($form);
        $this->assertEquals(1, $crawler->filter('li:contains("testCUpdate")')->count());

    }
}