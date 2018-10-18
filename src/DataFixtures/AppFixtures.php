<?php
/**
 * Created by PhpStorm.
 * User: aresc002
 * Date: 11/10/18
 * Time: 10:48
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Quote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $serialier = new Serializer([new GetSetMethodNormalizer(), new ArrayDenormalizer()], [new JsonEncoder()]);
        $datas = $serialier->deserialize(file_get_contents('var/quotes.json'), 'App\Entity\Quote[]', 'json');

        $cat = new Category();
        $cat->setName("Category de quotes");
        $manager->persist($cat);

        foreach ($datas as $data) {
            $quote = new Quote();
            $quote->setMeta($data->getMeta());
            $quote->setQuote($data->getQuote());
            $quote->setCategory($cat);
            $manager->persist($quote);
        }

        $manager->flush();


    }
}
