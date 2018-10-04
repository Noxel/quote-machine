<?php

namespace App\Entity;

use App\Repository\QuoteRepository;
use Symfony\Component\HttpKernel\DataCollector\DumpDataCollector;
use Symfony\Component\Validator\Constraints as Assert;

class Quote
{
    protected $id;

    /**
     * @Assert\NotBlank()
     */
    protected $quote;

    /**
     * @Assert\NotBlank()
     */
    protected $meta;


    public function getId()
    {
        return $this->id;
    }

    public function getQuote()
    {
        return $this->quote;
    }

    public function getMeta()
    {
        return $this->meta;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setQuote($q)
    {
        $this->quote = $q;
    }

    public function setMeta($m)
    {
        $this->meta = $m;
    }

    /**
     * Methode de recherche en fonction du POST[quote][search]
     *
     * @return array de Quote
     */
    public static function search()
    {
        $quotes = [];
        $quoteRep = new QuoteRepository('../var/quotes.json');
        if (isset($_POST['quote_search']['search'])) {
            $src = $_POST['quote_search']['search'];

            if (isset($src)) {
                foreach ($quoteRep->findAll() as $quote) {
                    if (stripos($quote->getQuote(), $src) !== false) {
                        $quotes[] = $quote;
                    }
                }
            }
        } else {
            $quotes = $quoteRep->findAll();
        }

        return $quotes;
    }
}
