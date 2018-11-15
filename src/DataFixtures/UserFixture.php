<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function  __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
       $user = new User();
       $user->setUsername('toto');

       $user->setPassword($this->passwordEncoder->encodePassword(
           $user,
           'the_new_password'
       ));

        $manager->persist($user);
        $manager->flush();
    }
}
