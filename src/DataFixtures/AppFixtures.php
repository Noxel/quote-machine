<?php
/**
 * Created by PhpStorm.
 * User: aresc002
 * Date: 11/10/18
 * Time: 10:48
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Quote;
use App\Util\Slugger;
use App\DataFixtures\UserFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $serialier = new Serializer([new GetSetMethodNormalizer(), new ArrayDenormalizer()], [new JsonEncoder()]);
        $datas = $serialier->deserialize(file_get_contents('var/quotes.json'), 'App\Entity\Quote[]', 'json');

        $cat = new Category();
        $cat->setName("Category de quotes");
        $cat->setSlug(Slugger::slugify($cat->getName()));
        $manager->persist($cat);

        foreach ($datas as $data) {
            $quote = new Quote();
            $quote->setMeta($data->getMeta());
            $quote->setQuote($data->getQuote());
            $quote->setOwner($this->getReference(UserFixture::USER_REFERENCE));
            $quote->setCategory($cat);
            $manager->persist($quote);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixture::class,
        );
    }
}
