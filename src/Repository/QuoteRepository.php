<?php

namespace App\Repository;

use App\Entity\Quote;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class QuoteRepository
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var Quote[]
     */
    private $quotes;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->serializer = new Serializer([new GetSetMethodNormalizer(), new ArrayDenormalizer()], [new JsonEncoder()]);
        $this->quotes = $this->serializer->deserialize(file_get_contents($this->filename), 'App\Entity\Quote[]', 'json');
    }

    /**
     * @param string $id
     *
     * @return Quote|null
     */
    public function find(string $id)
    {
        foreach ($this->quotes as $quote) {
            if ($quote->getId() == $id) {
                return $quote;
            }
        }
    }

    public function findAll(): array
    {
        return $this->quotes;
    }

    public function persist(Quote $quote)
    {
        if (null !== $quote->getId()) {
            return;
        }

        $quote->setId(uniqid());
        $this->quotes[] = $quote;
    }

    public function delete(Quote $quoteToDelete)
    {
        foreach ($this->quotes as $index => $quote) {
            if ($quote->getId() == $quoteToDelete->getId()) {
                unset($this->quotes[$index]);
            }
        }
    }

    public function __destruct()
    {
        $json = $this->serializer->serialize(array_values($this->quotes), 'json', ['json_encode_options' => JSON_PRETTY_PRINT]);
        file_put_contents($this->filename, $json);
    }
}
