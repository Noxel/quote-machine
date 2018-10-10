<?php

namespace App\Entity;

use App\Repository\QuoteRepository;
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


}
