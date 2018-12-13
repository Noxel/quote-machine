<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class UserFixture extends Fixture
{
    public const USER_REFERENCE ='user';

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setUsername('user');
        $user->setRoles(['ROLE_USER']);

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'user'
        ));

        $manager->persist($user);

        $this->addReference(self::USER_REFERENCE, $user);

        $user2 = new User();
        $user2->setUsername('user2');
        $user2->setRoles(['ROLE_USER']);

        $user2->setPassword($this->passwordEncoder->encodePassword(
            $user2,
            'user2'
        ));

        $manager->persist($user2);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);

        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'admin'
        ));

        $manager->persist($admin);

        $manager->flush();
    }
}
